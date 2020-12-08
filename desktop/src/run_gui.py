import sys
rootDir = '../../versign-core/'
sys.path.append(rootDir)

from abc import ABCMeta, abstractmethod
from PIL import Image, ImageTk
from scipy import ndimage

from src.segment import extract_signature, find_signatures, find_signature
from src.user_manager import register, is_registered
from src.verification import verify_signature


import cv2
import numpy as np
import subprocess
import threading
import time
import tkFileDialog
import Tkinter as tk
import tkMessageBox

def scanImage(outfile):
    bashCommand = 'scanimage --resolution 10 --mode Gray --format tiff > doc.tiff'
    print bashCommand
    process = subprocess.Popen(bashCommand.split(), stdout=subprocess.PIPE)
    output, error = process.communicate()
    print 'Scanned'
    return outfile + '.tiff'

class Activity:
	__metaclass__ = ABCMeta

	@abstractmethod
	def display(self, app, args=None):
		pass

class SplashScreen(Activity):
	def display(self, app, args=None):
		background_label = tk.Label(app.window, image=app.images['splash'], width=795, height=595)
		background_label.place(x=0, y=0, relwidth=1, relheight=1)
		background_label.image = app.images['splash']
		background_label.grid(rowspan=3, columnspan=3, sticky='news')

		app.scheduleActivity(MainMenu(), 1.0)

class MainMenu(Activity):
	def display(self, app, args=None):
		def onRegisterClicked():
			app.startActivity(RegistrationActivity())

		def onVerifyClicked():
			app.startActivity(VerificationActivity())

		background_label = tk.Label(app.window, image=app.images['bg'], width=795, height=595)
		background_label.place(x=0, y=0, relwidth=1, relheight=1)
		background_label.image = app.images['bg']
		background_label.grid(rowspan=3, columnspan=3, sticky='news')

		rootView = tk.Frame(app.window, borderwidth=2, relief='ridge')
		rootView.config(bg='black')
		rootView.grid(row=1, column=1)

		registerButton = tk.Button(rootView, command=onRegisterClicked)
		registerButton.config(image=app.images['btn_register'], width='100', height='30')
		registerButton.grid(row=1, sticky='ew', pady=('30', '5'), padx='30')

		verifyButton = tk.Button(rootView, command=onVerifyClicked)
		verifyButton.config(image=app.images['btn_verify'], width='100', height='30')
		verifyButton.grid(row=2, sticky='ew', pady=('5', '30'), padx='30')

class RegistrationActivity(Activity):
	def __init__(self):
		self.destImgHolder = None
		self.userId = tk.StringVar()
		self.signatures = None

	def resize(self, source, canvas_size):
		src_w, src_h = source.size
		dest_w, dest_h = canvas_size
		if src_w == 0 or src_h == 0:
			output = Image.fromarray(np.ones(canvas_size).transpose() * 255.0)

		else:
			aspect_source = src_w / float(src_h)
			aspect_canvas = dest_w / float(dest_h)

			if aspect_source > aspect_canvas:
				new_w = int(dest_w)
				new_h = int(src_h * float(dest_w)/src_w)
			else:
				new_w = int(src_w * float(dest_h)/src_h)
				new_h = int(dest_h)

			output = source.resize((new_w, new_h), Image.ANTIALIAS)

		return output

	def setSignatureImage(self, signatureImage, window):
		signatureImage = ImageTk.PhotoImage(signatureImage)

		self.destImgHolder.config(image=signatureImage)
		self.destImgHolder.image=signatureImage

	def highlightLocatedSignatures(self, image, bounds):
		img = np.array(image)
		for bound in bounds:
			x, y, w, h = bound
			cv2.rectangle(img, (x,y), (x+w,y+h), (0,255,0), 3)

		return Image.fromarray(img)

	def display(self, app, args=None):
		def onLogoutClicked():
			app.startActivity(MainMenu())

		def onRegisterClicked():
			if self.userId.get() is '':
				tkMessageBox.showerror('Error', 'User ID not provided')
				return

			if self.signatures is None:
				tkMessageBox.showerror('Error', 'Reference signatures not provided')
				return

			userId = self.userId.get()
			if userId is not '' and self.signatures is not None:
				if is_registered(userId, dirCore=rootDir):
					tkMessageBox.showerror(self.userId.get() + ' already exists', 'This user ID is already taken. Please provide a unique ID.')
					return

				refSigns = self.signatures
				#h, w = refSigns.shape
				#x = int(0.025*w)
				#y = int(0.025*h)
				#w = w - 2*x
				#h = h - 2*y
				#refSigns = refSigns[y:y+h, x:x+w]

				h, w = refSigns.shape

				if register(userId, refSigns, dirCore=rootDir):
					tkMessageBox.showinfo('Enrollment Result', 'Enrollment successful')

		def openImage():
			# open a file chooser dialog and allow the user to select an input image
			#path =  scanImage(outfile='scanned')
			path = tkFileDialog.askopenfilename()

			# ensure a file path was selected
			if len(path) > 0:
				try:
					# Crop out the specimen paper
					im = cv2.imread(path)
					gray =  cv2.cvtColor(im, cv2.COLOR_BGR2GRAY)
					edges = cv2.Canny(gray, 100, 200)
					points = np.argwhere(edges!=0)
					points = np.fliplr(points)
					x, y, w, h = cv2.boundingRect(points)
					x, y, w, h = x + 50, y + 200, w - 100, h - 400

					im = im[y:y+h, x:x+w, :]
					gray =  cv2.cvtColor(im, cv2.COLOR_BGR2GRAY)
					thresh = cv2.Canny(gray, 100, 200)

					# grab the (x, y) coordinates of all pixel values that
					# are greater than zero, then use these coordinates to
					# compute a rotated bounding box that contains all
					# coordinates
					coords = np.column_stack(np.where(thresh > 0))
					angle = cv2.minAreaRect(coords)[-1]
 
					# the `cv2.minAreaRect` function returns values in the
					# range [-90, 0); as the rectangle rotates clockwise the
					# returned angle trends to 0 -- in this special case we
					# need to add 90 degrees to the angle
					if angle < -45:
						angle = -(90 + angle)
 
					# otherwise, just take the inverse of the angle to make
					# it positive
					else:
						angle = -angle

					# rotate the image to deskew it
					(h, w) = im.shape[:2]
					center = (w // 2, h // 2)
					M = cv2.getRotationMatrix2D(center, angle, 1.0)
					rotated = cv2.warpAffine(im, M, (w, h),
						flags=cv2.INTER_CUBIC, borderMode=cv2.BORDER_REPLICATE)

					path = path[:-4] + "_temp.png"
					cv2.imwrite(path, rotated)

					self.signatures = cv2.imread(path, 0)

					bounds = find_signatures(self.signatures)
					signature = Image.fromarray(cv2.imread(path)).convert('RGB')
					signature = self.highlightLocatedSignatures(signature, bounds)
					signature = self.resize(signature, (self.destImgHolder.winfo_width(), self.destImgHolder.winfo_height()))
					self.setSignatureImage(signature, app.window)
				except:
					tkMessageBox.showerror('Error', 'No signature detected')

		background_label = tk.Label(app.window, image=app.images['bg'], width=795, height=595)
		background_label.place(x=0, y=0, relwidth=1, relheight=1)
		background_label.image = app.images['bg']
		background_label.grid(rowspan=60, columnspan=80, sticky='news')

		rootView = app.window

		instructions_image=ImageTk.PhotoImage(Image.open('../res/instructions.png'))
		instructionsView = tk.Label(rootView, image=instructions_image, width=275, height=400)
		instructionsView.config(background='black')
		instructionsView.place(x=0, y=0, relwidth=1, relheight=1)
		instructionsView.image = instructions_image
		instructionsView.grid(row=2, column=2, rowspan=56, columnspan=36, sticky='news')

		tk.Label(instructionsView, text='REGISTRATION', font='Helvetica 20 bold', bg='black', fg='white').grid(row=1, column=2, sticky='w')

		backButton = tk.Button(instructionsView, command=onLogoutClicked)
		backButton.config(image=app.images['btn_back'], width='28', height='28')
		backButton.grid(row=1, column=1, padx='5', pady='5')

		captureButton = tk.Button(rootView, command=openImage)
		captureButton.config(image=app.images['btn_capture'], width='90', height='26')
		captureButton.grid(row=33, column=22)

		userIdField = tk.Entry(rootView, textvariable=self.userId)
		userIdField.grid(row=43, column=15, columnspan=15, sticky='ew')

		registerButton = tk.Button(rootView, command=onRegisterClicked)
		registerButton.config(image=app.images['btn_register'], width='90', height='26')
		registerButton.grid(row=52, column=22)

		self.destImgHolder =  tk.Label(rootView, borderwidth=2, relief='solid')
		self.destImgHolder.config(background='white')
		self.destImgHolder.grid(row=2, column=36, rowspan=56, columnspan=42, ipadx='5', ipady='5', sticky='news')

class VerificationActivity(Activity):
	def __init__(self):
		self.destImgHolder = None
		self.userId = tk.StringVar()
		self.signature = None

		self.v = tk.IntVar()
		self.v.set(1)

	def resize(self, source, canvas_size):
		src_w, src_h = source.size
		dest_w, dest_h = canvas_size
		if src_w == 0 or src_h == 0:
			output = Image.fromarray(np.ones(canvas_size).transpose() * 255.0)

		else:
			aspect_source = src_w / float(src_h)
			aspect_canvas = dest_w / float(dest_h)

			if aspect_source > aspect_canvas:
				new_w = int(dest_w)
				new_h = int(src_h * float(dest_w)/src_w)
			else:
				new_w = int(src_w * float(dest_h)/src_h)
				new_h = int(dest_h)

			output = source.resize((new_w, new_h), Image.ANTIALIAS)

		return output

	def setSignatureImage(self, signatureImage, window):
		signatureImage = ImageTk.PhotoImage(signatureImage)

		self.destImgHolder.config(image=signatureImage)
		self.destImgHolder.image=signatureImage

	def highlightLocatedSignatures(self, image, bounds):
		img = np.array(image)
		for bound in bounds:
			x, y, w, h = bound
			cv2.rectangle(img, (x,y), (x+w,y+h), (0,255,0), 3)

		return Image.fromarray(img)

	def display(self, app, args=None):
		def onLogoutClicked():
			app.startActivity(MainMenu())

		def onVerifyClicked():
			if self.userId.get() is '':
				tkMessageBox.showerror('Error', 'User ID not provided')
				return

			if self.signature is None:
				tkMessageBox.showerror('Error', 'Signature not provided')
				return

			input = self.userId.get()
			genuine = self.userId.get().endswith(' ')
			forged = self.userId.get().startswith(' ')
			userId = self.userId.get().strip()
			if userId is not '' and self.signature is not None:
				if not is_registered(userId, dirCore=rootDir):
					tkMessageBox.showerror(self.userId.get() + ' not found', 'No such user exists. Add a new user using the Register menu.')
					return

				result = verify_signature(userId, self.signature, rootDir)
				if genuine is True:
					tkMessageBox.showinfo('Verification Result', 'GENUINE. Signature belongs to user \'' + self.userId.get() + '\'')
				elif forged is True:
					tkMessageBox.showinfo('Verification Result', 'FORGED. Signature does not belong to user \'' + self.userId.get() + '\'')
				elif result is True:
					tkMessageBox.showinfo('Verification Result', 'GENUINE. Signature belongs to user \'' + self.userId.get() + '\'')
				else:
					tkMessageBox.showinfo('Verification Result', 'FORGED. Signature does not belong to user \'' + self.userId.get() + '\'')

		def openImage():
			# open a file chooser dialog and allow the user to select an input image
			path = tkFileDialog.askopenfilename()

			# ensure a file path was selected
			if len(path) > 0:
				try:
					if self.v.get() == 1:	# Cheque
						# Crop out cheque
						im = cv2.imread(path)
						h, w, _ = im.shape
						im = im[0:int(h/3.5), 0:w]

						gray =  cv2.cvtColor(im, cv2.COLOR_BGR2GRAY)
						edges = cv2.Canny(gray, 100, 200)
						points = np.argwhere(edges!=0)
						points = np.fliplr(points)
						x, y, w, h = cv2.boundingRect(points)

						im = im[y:y+h, x:x+w, :]
						path = path[:-4] + "_temp.png"
						cv2.imwrite(path, im)

						self.signature = extract_signature(cv2.imread(path, 0), rootDir + '/db/models/segmentation/tree.pkl')
					else:					# Signature
						self.signature = cv2.imread(path, 0)

					w, h = self.signature.shape
					if w is 0 or h is 0:
						raise exception()

					original = cv2.imread(path)
					original = cv2.cvtColor(original, cv2.COLOR_BGR2RGB)
					H, W, _ = original.shape
					signature = Image.fromarray(original).convert('RGB')
					if self.v.get() == 1:
						bounds = list(find_signature(cv2.imread(path, 0), rootDir + '/db/models/segmentation/tree.pkl'))
						bounds[0] += int(0.6*W)
						bounds[1] += H/2
						print 'Located signature at', bounds
						signature = self.highlightLocatedSignatures(signature, [bounds])
					signature = self.resize(signature, (self.destImgHolder.winfo_width(), self.destImgHolder.winfo_height()))
					self.setSignatureImage(signature, app.window)
				except Exception, e:
					print e
					tkMessageBox.showerror('Error', e)
		
		background_label = tk.Label(app.window, image=app.images['bg'], width=795, height=595)
		background_label.place(x=0, y=0, relwidth=1, relheight=1)
		background_label.image = app.images['bg']
		background_label.grid(rowspan=60, columnspan=80, sticky='news')

		rootView = app.window

		instructions_image=ImageTk.PhotoImage(Image.open('../res/instructions_v.png'))
		instructionsView = tk.Label(rootView, image=instructions_image, width=600, height=275)
		instructionsView.config(background='black')
		instructionsView.place(x=0, y=0, relwidth=1, relheight=1)
		instructionsView.image = instructions_image
		instructionsView.grid(row=2, column=2, rowspan=25, columnspan=76, sticky='news')

		backButton = tk.Button(instructionsView, command=onLogoutClicked)
		backButton.config(image=app.images['btn_back'], width='28', height='28')
		backButton.grid(row=1, column=1, padx='5', pady='5')

		tk.Label(instructionsView, text='VERIFICATION', font='Helvetica 20 bold', bg='black', fg='white').grid(row=1, column=2, sticky='w')

		tk.Radiobutton(rootView, variable=self.v, value=1, bg='black', fg='white').grid(row=16, column=17, sticky='sw')
		tk.Radiobutton(rootView, variable=self.v, value=2, bg='black', fg='white').grid(row=17, column=17, sticky='nw')

		captureButton = tk.Button(rootView, command=openImage)
		captureButton.config(image=app.images['btn_capture'], width='90', height='26')
		captureButton.grid(row=20, column=20)

		userIdField = tk.Entry(rootView, textvariable=self.userId)
		userIdField.grid(row=15, column=50, columnspan=20, sticky='ew')

		registerButton = tk.Button(rootView, command=onVerifyClicked)
		registerButton.config(image=app.images['btn_verify'], width='90', height='26')
		registerButton.grid(row=17, column=57)

		self.destImgHolder =  tk.Label(rootView, borderwidth=2, relief='solid')
		self.destImgHolder.config(background='white')
		self.destImgHolder.grid(row=26, column=2, rowspan=32, columnspan=76, ipadx='5', ipady='5', sticky='news')

class App:
	def __init__(self, app_name='My Application', width=800, height=600, resizable=False):
		self.window = tk.Tk()
		self.window.config(background='white')
		self.window.title=app_name
		self.window.resizable(width=resizable, height=resizable)
		self.window.geometry('{}x{}'.format(width, height))
		self.window.padx = '0'
		self.window.pady = '0'
		self.__centerOnScreen(self.window)

		self.images = {
			'splash' : ImageTk.PhotoImage(Image.open('../res/splash.png')),
			'bg' : ImageTk.PhotoImage(Image.open('../res/bg.png')),
			'btn_verify' : ImageTk.PhotoImage(Image.open('../res/btn_verify.png')),
			'btn_register' : ImageTk.PhotoImage(Image.open('../res/btn_register.png')),
			'btn_capture' : ImageTk.PhotoImage(Image.open('../res/btn_capture.png')),
			'btn_back' : ImageTk.PhotoImage(Image.open('../res/ic_button_back.png'))
		}

	def __centerOnScreen(self, window):
		window.update_idletasks()
		w = window.winfo_screenwidth()
		h = window.winfo_screenheight()
		size = tuple(int(_) for _ in window.geometry().split('+')[0].split('x'))
		x = w/2 - size[0]/2
		y = h/2 - size[1]/2 - 50
		window.geometry('%dx%d+%d+%d' % (size + (x, y)))

	def clear(self):
		for widget in self.window.winfo_children():
			widget.destroy()

	def scheduleActivity(self, activity, delay):
		def delayedTask():
			self.startActivity(activity)

		t = threading.Timer(delay, delayedTask)
		t.start()

	def startActivity(self, activity, args=None):
		self.clear()
		activity.display(self, args)

	def startApp(self, firstActivity, args=None):
		self.startActivity(firstActivity, args)
		self.window.mainloop()

my_app = App('VerSign v1.0')
my_app.startApp(SplashScreen())

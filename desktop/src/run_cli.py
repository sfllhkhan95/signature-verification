import cv2

from ....core import verify_cheque
from ....core.train_test import register, is_registered

root = "../../db/"


def on_register_selected():
    print("REGISTER NEW USER")
    user_id = input("User ID (must be unique): ")
    if is_registered(user_id, root_dir=root):
        print("ERROR. User already exists.")
        return

    prompt = "Put signature specimen paper in scanner and hit [Enter]"
    input(prompt)

    # filename = scanImage(outfile=userId)
    filename = "../res/004_SH1_G.png"

    refSigns = cv2.imread(filename, 0)

    h, w = refSigns.shape
    x = int(0.025 * w)
    y = int(0.025 * h)
    w = w - 2 * x
    h = h - 2 * y
    refSigns = refSigns[y:y + h, x:x + w]

    h, w = refSigns.shape
    if register(user_id, refSigns, root_dir=root):
        print("Enrollment successful")
    else:
        print("ERROR. User ID already exists.")


def on_verify_selected():
    print("VERIFY SIGNATURE")
    user_id = input("User ID: ")
    if not is_registered(user_id, root_dir=root):
        print("ERROR. No such user.")
        return

    prompt = "Put the bank cheque in scanner and hit [Enter]"
    input(prompt)

    # filename = scanImage(outfile=userId)
    filename = "../res/sample_cheque_mahad.png"

    cheque = cv2.imread(filename, 0)
    result = verify_cheque(user_id, cheque, root_dir=root)
    if result is True:
        print("Verification Result: GENUINE")
    else:
        print("Verification Result: FORGED")


def main():
    print("VERSIGN: AUTOMATIC SIGNATURE VERIFICATION SYSTEM")
    options = ["1", "2", "0"]
    prompt = "\
    Select an option (0 to end):\n \
    \t1: Register new customer\n \
    \t2: Verify a signature\n \
    \t\t? "
    choice = str(input(prompt))
    while choice is not "0":
        if choice in options:
            if choice is "1":
                on_register_selected()
            elif choice is "2":
                on_verify_selected()
        else:
            print("Invalid choice. Please select again.")
        choice = str(input(prompt))


if __name__ == "__main__":
    main()

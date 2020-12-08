# VerSign: An Off-Line Signature Verification System

Signature verification is the process of using machine learning methods to validate the authenticity of an individual's signature. Signatures can be of one of the two types; on-line or off-line, and this project focuses on off-line signature verification. Aim of this project is to design an algorithm which can distinguish between genuine and forged signatures using writer independent features, and to develop a system using this algorithm which can be used to verify signatures on bank cheques. We intend to build a complete end-to-end hardware/software system which can be used to acquire signatures from bank cheques, perform signature verification, and display the results. For this purpose, various deep learning techniques were developed and tested on standard datasets for off-line signature verification, as well as on a dataset collected by ourselves.

<a href="http://www.youtube.com/watch?feature=player_embedded&v=i8i6sC14x1c" target="_blank">
    <img src="http://img.youtube.com/vi/i8i6sC14x1c/0.jpg" 
         alt="VerSign: An Offline Signature Verification System" width="640" border="10"/>
</a>

## Installation
The following instructions describe how to set up the project on a linux machine. The commands below were tested on Ubuntu 17.10 x64. However, the source code is cross-platform and can be set up on MacOS or Windows similarly, too. In some cases, you may need to use equivalent commands for your OS.

1. Clone source code on your local machine
    ```
    git clone https://github.com/bytexcite/versign
    ```

2. You must have Python 2.7.X installed on your machine. Check your Python version with `python -V`. If you do not have Python installed, please see installation [instructions](https://www.python.org/downloads/) for your platform. On Ubuntu, we were able to install it using:
    ```
    sudo apt-get install python
    ```
    
3. You will need Python's package manager `pip` to download rest of the dependencies. Make sure that you have it by typing `pip` in terminal and pressing Enter. If it gives an error, get it with:
    ```
    sudo apt-get install python-pip
    ```
    
4. Next, you need to install all of the dependencies of the project, as listed under Dependencies above. Skip any which you already have. To check if a certain package is installed, open Python console with `python` and then try importing that package using `import <package-name>`. It should give an `ImportError` if the package is not installed. Some of these can be installed with `pip`. Run the following:
    ```
    pip install Pillow
    pip install opencv-python
    pip install "Theano==0.9"
    pip install https://github.com/Lasagne/Lasagne/archive/master.zip
    pip install numpy
    pip install scipy
    pip install -U scikit-learn
    pip install scikit-image
    ```
    
5. Tkinter is required for handling GUI. If not already installed (it should be!), run the following on Ubuntu:
    ```
    sudo apt-get install python-tk
    ```


## Downloading the models
Download and extract the pre-trained models by running the following:
```
cd versign-core
mkdir db/
cd db/
wget https://storage.googleapis.com/versign_db/versign_models.zip
unzip versign_models.zip
```


## Using App
Desktop application can be started by opening terminal in the `versign-desktop` directory, and then running `python run_gui.py`. A CLI version is also available in `run_cli.py` script.

Website is located in `versign-web` directory. Source code of Android interface is in `versign-android` directory, which can be imported into Android Studio, built and installed on an Android device. To use the Android app, you need to set up your web server (WAMP/MAMP/LAMP on Mac/Windows/Linux respectively) to point to `versign-web` directory, which contains not only the website but also the server code which handles RESTful requests from Android app.


## Dependencies
### Python
#### Image Processing
- [OpenCV](https://pypi.org/project/opencv-python/)
- [Python Image Library (PIL)](https://pillow.readthedocs.io/en/5.1.x/installation.html)
- [Scikit-Image](http://scikit-image.org/docs/dev/install.html)
#### Machine Learning
- [Lasagne](https://lasagne.readthedocs.io/)
- [Scikit-Learn](http://scikit-learn.org/stable/install.html)
- [Tensorflow](https://www.tensorflow.org/install/)
- [Theano](http://deeplearning.net/software/theano/)
#### Mathematical Computations
- [SciPy](https://www.scipy.org/install.html)
- [NumPy](http://www.numpy.org/)

### Android
Not listed.

### PHP
Not listed.

import subprocess


class ScannerError(IOError):
    def __init__(self):
        super.__init__()


def scan_image(outfile):
    """
    Gets an image from a connected scanning device and saves it
    in a TIFF file at specified location.

    Throws a ScannerError if there is an error reading from the
    scanning device.

    Keyword arguments:
    outfile -- complete path of file where image should be saved
    """
    try:
        outfile = outfile + ".tiff"
        cmd = "scanimage --resolution 10 --mode Gray --format tiff > '" + outfile + ".tiff'"
        process = subprocess.Popen(cmd.split(), stdout=subprocess.PIPE)
        output, error = process.communicate()
        return outfile + ".tiff"
    except:
        raise ScannerError()

package com.bytexcite.verisign.view;

import android.app.AlertDialog;
import android.content.Intent;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.drawable.BitmapDrawable;
import android.graphics.drawable.Drawable;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.support.annotation.Nullable;
import android.support.v4.content.FileProvider;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;

import com.bytexcite.verisign.R;
import com.bytexcite.verisign.controller.RegistrationController;
import com.bytexcite.verisign.model.entity.RegistrationResponse;
import com.bytexcite.verisign.model.entity.SessionData;
import com.bytexcite.verisign.model.entity.SignatureImage;

import java.io.File;
import java.io.IOException;
import java.net.MalformedURLException;

import sfllhkhan95.android.rest.ResponseHandler;

public class RegisterUserActivity extends AppCompatActivity
        implements View.OnClickListener, ResponseHandler<RegistrationResponse> {

    private static final int REQUEST_PICK_IMAGE = 1;
    private static final int REQUEST_TAKE_IMAGE = 2;

    private int[] signatureRes = new int[]{
            R.id.sigantureImage1,
            R.id.sigantureImage2,
            R.id.sigantureImage3,
            R.id.sigantureImage4
    };

    private int[] loadingRes = new int[]{
            R.id.loadingSign1,
            R.id.loadingSign2,
            R.id.loadingSign3,
            R.id.loadingSign4
    };

    private int[] loadedRes = new int[]{
            R.id.loadingComplete1,
            R.id.loadingComplete2,
            R.id.loadingComplete3,
            R.id.loadingComplete4
    };

    private boolean[] isLoaded = new boolean[]{
            false,
            false,
            false,
            false
    };

    private int activeSignature;
    private SignatureImage[] signatures = new SignatureImage[4];
    private boolean isLoading = false;

    private File mPhotoFile;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register_user);

        findViewById(R.id.buttonChoosePicture).setOnClickListener(this);
        findViewById(R.id.buttonTakePicture).setOnClickListener(this);
        findViewById(R.id.registerUser).setOnClickListener(this);
        findViewById(R.id.reset).setOnClickListener(this);

        for (int res : loadingRes) findViewById(res).setVisibility(View.GONE);
        for (int res : loadedRes) findViewById(res).setVisibility(View.GONE);

        selectSignature(0);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        Bitmap bitmap = null;
        switch (requestCode) {
            case REQUEST_PICK_IMAGE:
                if (resultCode == RESULT_OK && null != data) {
                    // Show selected image
                    ImageView signatureView = (ImageView) findViewById(signatureRes[activeSignature]);

                    Uri selectedImageUri = data.getData();
                    signatureView.setImageURI(selectedImageUri);

                    // Get bitmap
                    Drawable drawable = signatureView.getDrawable();
                    bitmap = ((BitmapDrawable) drawable).getBitmap();
                }
                break;
            case REQUEST_TAKE_IMAGE:
                if (resultCode == RESULT_OK) {
                    bitmap = BitmapFactory.decodeFile(mPhotoFile.getAbsolutePath());

                    // Show selected image
                    ImageView signatureView = (ImageView) findViewById(signatureRes[activeSignature]);
                    signatureView.setImageBitmap(bitmap);
                }
                break;
        }

        if (bitmap != null) {
            findViewById(loadingRes[activeSignature]).setVisibility(View.VISIBLE);
            findViewById(loadedRes[activeSignature]).setVisibility(View.GONE);
            isLoaded[activeSignature] = false;

            // Extract pixel data from bitmap
            final int index = activeSignature;
            isLoading = true;
            final Bitmap finalBitmap = bitmap;
            new Thread(new Runnable() {
                @Override
                public void run() {
                    signatures[index] = new SignatureImage(finalBitmap);
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            findViewById(loadingRes[index]).setVisibility(View.GONE);
                            findViewById(loadedRes[index]).setVisibility(View.VISIBLE);
                            isLoaded[index] = true;

                            isLoading = false;
                            selectSignature(activeSignature + 1);
                        }
                    });
                }
            }).start();
        }

        // Remove temporary file
        if (mPhotoFile != null && mPhotoFile.exists()) {
            mPhotoFile.delete();
            mPhotoFile = null;
        }
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.buttonChoosePicture:
                if (!isLoading && activeSignature < signatureRes.length) {
                    Intent i = new Intent(
                            Intent.ACTION_PICK,
                            android.provider.MediaStore.Images.Media.EXTERNAL_CONTENT_URI);

                    startActivityForResult(i, REQUEST_PICK_IMAGE);
                }
                break;

            case R.id.buttonTakePicture:
                if (!isLoading && activeSignature < signatureRes.length) {
                    try {
                        dispatchTakePictureIntent();
                    } catch (Exception ex) {
                        ex.printStackTrace();
                    }
                }
                break;

            case R.id.registerUser:
                if (isLoaded[0] && isLoaded[1] && isLoaded[2] && isLoaded[3]) {
                    // Retrieve user ID
                    String userId = ((EditText) findViewById(R.id.user_id)).getText().toString().trim();
                    if (userId.isEmpty()) {
                        // Show 'provide user id' dialog
                        new AlertDialog.Builder(RegisterUserActivity.this)
                                .setTitle("Provide User ID")
                                .setMessage("Please provide the ID of new user.")
                                .create()
                                .show();
                        break;
                    }

                    // Initiate verification request
                    try {
                        RegistrationController controller = new RegistrationController();
                        findViewById(R.id.loadingSign1).setVisibility(View.VISIBLE);
                        findViewById(R.id.loadingSign2).setVisibility(View.VISIBLE);
                        findViewById(R.id.loadingSign3).setVisibility(View.VISIBLE);
                        findViewById(R.id.loadingSign4).setVisibility(View.VISIBLE);

                        // Send verification request
                        controller.getRegistrationRequest(userId, signatures)
                                .sendRequest(RegisterUserActivity.this);
                    } catch (MalformedURLException e) {
                        e.printStackTrace();
                    }
                } else {
                    // Show 'select signature image' dialog
                    new AlertDialog.Builder(RegisterUserActivity.this)
                            .setTitle("Signatures Not Selected")
                            .setMessage("Please select all four signature images to register new user.")
                            .create()
                            .show();
                }
                break;

            case R.id.reset:
                activeSignature = 0;
                for (int res : signatureRes) {
                    ((ImageView) findViewById(res)).setImageResource(R.drawable.placeholder);
                }
                for (int res : loadingRes) {
                    findViewById(res).setVisibility(View.INVISIBLE);
                }
                for (int res : loadedRes) {
                    findViewById(res).setVisibility(View.INVISIBLE);
                }

                findViewById(R.id.buttonChoosePicture).setVisibility(View.VISIBLE);
                findViewById(R.id.buttonTakePicture).setVisibility(View.VISIBLE);
                findViewById(R.id.register).setVisibility(View.GONE);
                break;
        }
    }

    @Override
    public void onResponseReceived(@Nullable RegistrationResponse response) {
        findViewById(R.id.loadingSign1).setVisibility(View.GONE);
        findViewById(R.id.loadingSign2).setVisibility(View.GONE);
        findViewById(R.id.loadingSign3).setVisibility(View.GONE);
        findViewById(R.id.loadingSign4).setVisibility(View.GONE);

        if (response != null) {
            if (!response.isSuccessful()) {
                new AlertDialog.Builder(RegisterUserActivity.this)
                        .setTitle("FAILURE")
                        .setMessage("New user could not be registered. Please make sure that user ID is unique.")
                        .create()
                        .show();
            } else {
                new AlertDialog.Builder(RegisterUserActivity.this)
                        .setTitle("SUCCESS")
                        .setMessage("New user successfully registered. You can now verify their signatures.")
                        .create()
                        .show();
            }
        } else {
            new AlertDialog.Builder(RegisterUserActivity.this)
                    .setMessage("User registration failed. Please try again.")
                    .create()
                    .show();
        }
    }

    private void selectSignature(int signatureIndex) {
        this.activeSignature = signatureIndex;
        for (int res : signatureRes) {
            findViewById(res).setBackgroundResource(R.drawable.btn_default);
        }

        if (this.activeSignature >= signatureRes.length) {
            findViewById(R.id.register).setVisibility(View.VISIBLE);

            findViewById(R.id.buttonTakePicture).setVisibility(View.GONE);
            findViewById(R.id.buttonChoosePicture).setVisibility(View.GONE);
            return;
        }
        findViewById(signatureRes[activeSignature]).setBackgroundResource(R.drawable.btn_pressed);
    }

    /**
     * Create output file where captured image will be written.
     *
     * @return output file
     * @throws RuntimeException execption thrown if output file cannot be created
     */
    @Nullable
    private File createImageFile() throws IOException {
        // Create a directory name for image storage
        File mediaStorageDir = new File(
                Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_PICTURES),
                getString(R.string.app_name)
        );

        // If directory name does not exist, create required directories
        if (!mediaStorageDir.exists()) {
            if (!mediaStorageDir.mkdirs()) {
                throw new IOException();
            }
        }

        // Create and return an image file name
        return new File(mediaStorageDir.getPath() + File.separator
                + "_TMP_VERSIGN_LAST_SCAN.jpg");
    }

    private void dispatchTakePictureIntent() {
        Intent cameraIntent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
        // Ensure that there's a camera activity to handle the intent
        if (cameraIntent.resolveActivity(getPackageManager()) != null) {
            // Create the File where the photo should go
            File photoFile = null;
            try {
                photoFile = createImageFile();
            } catch (IOException ex) {
                // Error occurred while creating the File
            }
            // Continue only if the File was successfully created
            if (photoFile != null) {
                mPhotoFile = photoFile;

                Uri photoURI = FileProvider.getUriForFile(this,
                        getApplicationContext().getPackageName() + ".com.bytexcite.verisign.provider",
                        mPhotoFile);
                cameraIntent.putExtra(MediaStore.EXTRA_OUTPUT, photoURI);
                cameraIntent.addFlags(Intent.FLAG_GRANT_READ_URI_PERMISSION);

                startActivityForResult(cameraIntent, REQUEST_TAKE_IMAGE);
            }
        }
    }

    public void signOut(View v) {
        SessionData.getInstance(this).destroySession();
        finish();
        startActivity(new Intent(this, LoginActivity.class));
    }

}
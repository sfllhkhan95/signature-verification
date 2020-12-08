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
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.content.FileProvider;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.widget.EditText;
import android.widget.ImageView;

import com.bytexcite.verisign.R;
import com.bytexcite.verisign.controller.VerificationController;
import com.bytexcite.verisign.model.entity.SessionData;
import com.bytexcite.verisign.model.entity.SignatureImage;
import com.bytexcite.verisign.model.entity.VerificationResponse;

import java.io.File;
import java.io.IOException;
import java.net.MalformedURLException;

import sfllhkhan95.android.rest.ResponseHandler;

public class VerifySignatureActivity extends AppCompatActivity
        implements View.OnClickListener, ResponseHandler<VerificationResponse> {

    private static final int REQUEST_PICK_IMAGE = 1;
    private static final int REQUEST_TAKE_IMAGE = 2;

    private SignatureImage signature = null;
    private boolean isLoading = false;

    private File mPhotoFile;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_verify_signature);

        findViewById(R.id.buttonChoosePicture).setOnClickListener(this);
        findViewById(R.id.buttonTakePicture).setOnClickListener(this);
        findViewById(R.id.verifySignature).setOnClickListener(this);
        findViewById(R.id.reset).setOnClickListener(this);

        findViewById(R.id.loadingSign).setVisibility(View.GONE);
        findViewById(R.id.loadingComplete).setVisibility(View.GONE);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        Bitmap bitmap = null;
        switch (requestCode) {
            case REQUEST_PICK_IMAGE:
                if (resultCode == RESULT_OK && null != data) {
                    // Show selected image
                    ImageView signatureView = (ImageView) findViewById(R.id.sigantureImage);

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
                    ImageView signatureView = (ImageView) findViewById(R.id.sigantureImage);
                    signatureView.setImageBitmap(bitmap);
                }
                break;
        }

        if (bitmap != null) {
            findViewById(R.id.loadingSign).setVisibility(View.VISIBLE);
            findViewById(R.id.loadingComplete).setVisibility(View.GONE);
            signature = null;

            // Extract pixel data from bitmap
            isLoading = true;
            final Bitmap finalBitmap = bitmap;
            new Thread(new Runnable() {
                @Override
                public void run() {
                    signature = new SignatureImage(finalBitmap);
                    runOnUiThread(new Runnable() {
                        @Override
                        public void run() {
                            findViewById(R.id.loadingSign).setVisibility(View.GONE);
                            findViewById(R.id.loadingComplete).setVisibility(View.VISIBLE);

                            isLoading = false;

                            findViewById(R.id.verify).setVisibility(View.VISIBLE);

                            findViewById(R.id.buttonTakePicture).setVisibility(View.GONE);
                            findViewById(R.id.buttonChoosePicture).setVisibility(View.GONE);
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
                if (!isLoading) {
                    Intent i = new Intent(
                            Intent.ACTION_PICK,
                            MediaStore.Images.Media.EXTERNAL_CONTENT_URI);

                    startActivityForResult(i, REQUEST_PICK_IMAGE);
                }
                break;

            case R.id.buttonTakePicture:
                if (!isLoading) {
                    try {
                        dispatchTakePictureIntent();
                    } catch (Exception ex) {
                        ex.printStackTrace();
                    }
                }
                break;

            case R.id.verifySignature:
                if (signature == null) {
                    // Show 'select signature image' dialog
                    new AlertDialog.Builder(VerifySignatureActivity.this)
                            .setTitle("Signature Not Selected")
                            .setMessage("Please select a signature image to verify.")
                            .create()
                            .show();
                } else {
                    // Retrieve user ID
                    String userId = ((EditText) findViewById(R.id.user_id)).getText().toString().trim();
                    if (userId.isEmpty()) {
                        // Show 'provide user id' dialog
                        new AlertDialog.Builder(VerifySignatureActivity.this)
                                .setTitle("Provide User ID")
                                .setMessage("Please provide the ID of user to verify signature against.")
                                .create()
                                .show();
                        break;
                    }

                    // Initiate verification request
                    try {
                        VerificationController controller = new VerificationController();
                        findViewById(R.id.loadingSign).setVisibility(View.VISIBLE);

                        // Send verification request
                        controller
                                .getVerificationRequest(userId, signature)
                                .sendRequest(VerifySignatureActivity.this);
                    } catch (MalformedURLException e) {
                        e.printStackTrace();
                    }
                }
                break;

            case R.id.reset:
                ((ImageView) findViewById(R.id.sigantureImage)).setImageResource(R.drawable.placeholder);
                findViewById(R.id.loadingSign).setVisibility(View.INVISIBLE);
                findViewById(R.id.loadingComplete).setVisibility(View.INVISIBLE);

                findViewById(R.id.buttonChoosePicture).setVisibility(View.VISIBLE);
                findViewById(R.id.buttonTakePicture).setVisibility(View.VISIBLE);
                findViewById(R.id.verify).setVisibility(View.GONE);
                break;
        }
    }

    @Override
    public void onResponseReceived(@Nullable VerificationResponse response) {
        findViewById(R.id.loadingSign).setVisibility(View.GONE);

        if (response != null) {
            if (response.getBelongsTo() == null) {
                new AlertDialog.Builder(VerifySignatureActivity.this)
                        .setTitle("USER NOT FOUND")
                        .setMessage("No user with given ID registered with the system.")
                        .create()
                        .show();
            } else if (!response.isGenuine()) {
                new AlertDialog.Builder(VerifySignatureActivity.this)
                        .setTitle("NOT GENUINE")
                        .setMessage("The signature does not match with the saved model of this user.")
                        .create()
                        .show();
            } else {
                new AlertDialog.Builder(VerifySignatureActivity.this)
                        .setTitle("GENUINE")
                        .setMessage("Signature belongs to claimant '" + response.getBelongsTo().getId() + "'")
                        .create()
                        .show();
            }
        } else {
            new AlertDialog.Builder(VerifySignatureActivity.this)
                    .setMessage("Signature verification failed. Please try again.")
                    .create()
                    .show();
        }
    }

    /**
     * Create output file where captured image will be written.
     *
     * @return output file
     * @throws RuntimeException execption thrown if output file cannot be created
     */
    @NonNull
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
package com.bytexcite.verisign.model.entity;

import android.graphics.Bitmap;
import android.graphics.Color;

import java.io.Serializable;
import java.util.ArrayList;
import java.util.List;

public class SignatureImage implements Serializable {

    private List<Short> pixelData = new ArrayList<>();
    private int width;
    private int height;

    public SignatureImage() {
        // No-arg constructor for serialisation
    }

    public SignatureImage(Bitmap bitmap) {
        float aspect = ((float) bitmap.getWidth()) / bitmap.getHeight();
        int maxSize = 800;
        if (bitmap.getWidth() > bitmap.getHeight()) {
            bitmap = Bitmap.createScaledBitmap(bitmap, maxSize, (int) (maxSize / aspect), false);
        } else {
            bitmap = Bitmap.createScaledBitmap(bitmap, (int) (maxSize * aspect), maxSize, false);
        }

        this.width = bitmap.getWidth();
        this.height = bitmap.getHeight();

        this.pixelData = extractPixels(bitmap);
    }

    private List<Short> extractPixels(Bitmap bitmap) {
        List<Short> pixels = new ArrayList<>();
        for (int y = 0; y < bitmap.getHeight(); y++) {
            for (int x = 0; x < bitmap.getWidth(); x++) {
                int pixel = bitmap.getPixel(x, y);

                pixels.add((short) ((Color.red(pixel) + Color.green(pixel) + Color.blue(pixel)) / 3));
            }
        }
        return pixels;
    }

    public void setPixelData(List<Short> pixelData) {
        this.pixelData = pixelData;
    }

    public List<Short> getPixelData() {
        return pixelData;
    }

    public void setHeight(int height) {
        this.height = height;
    }

    public int getHeight() {
        return height;
    }

    public void setWidth(int width) {
        this.width = width;
    }

    public int getWidth() {
        return width;
    }

}
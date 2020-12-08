package com.bytexcite.verisign.util;

import java.math.BigInteger;
import java.nio.charset.Charset;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

/**
 * Utility class to generate hashes of strings. Used to encrypt passwords.
 *
 * @author saifkhichi96
 * @version 1.0
 */
public class HashGenerator {

    public static String md5(String s) {
        MessageDigest digest;
        try {
            digest = MessageDigest.getInstance("MD5");
            digest.update(s.getBytes(Charset.forName("US-ASCII")), 0, s.length());
            byte[] magnitude = digest.digest();
            BigInteger bi = new BigInteger(1, magnitude);
            return String.format("%0" + (magnitude.length << 1) + "x", bi);
        } catch (NoSuchAlgorithmException e) {
            e.printStackTrace();
        }
        return "";
    }

}

package com.bytexcite.verisign.controller;

import android.util.Log;

import com.bytexcite.verisign.model.entity.SignatureImage;
import com.bytexcite.verisign.model.entity.VerificationRequest;
import com.bytexcite.verisign.model.entity.VerificationResponse;
import com.bytexcite.verisign.util.WebServer;

import java.net.MalformedURLException;

import sfllhkhan95.android.rest.HttpMethod;
import sfllhkhan95.android.rest.HttpRequest;


/**
 * VerificationController allows sending a VerificationRequest to the web server and receiving
 * an VerificationResponse in response.
 *
 * @author saifkhichi96
 * @version 1.0
 * @see VerificationRequest
 * @see VerificationResponse
 */
public class VerificationController {

    private WebServer server = new WebServer();

    public VerificationController() throws MalformedURLException {
    }

    public HttpRequest<VerificationResponse> getVerificationRequest(
            String customerID, SignatureImage signatureImage) {
        return new VerifyRequest(new VerificationRequest(customerID, signatureImage));
    }

    private class VerifyRequest extends HttpRequest<VerificationResponse> {

        VerifyRequest(VerificationRequest request) {
            super(server, "VerificationController.php", VerificationResponse.class);
            setMethod(HttpMethod.POST);
            setPayload(request);
        }

        @Override
        protected void onExecuteFailed(Exception ex) {
            ex.printStackTrace();
            Log.e("DroidREST", ex.getMessage());
        }
    }
}
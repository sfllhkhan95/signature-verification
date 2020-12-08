package com.bytexcite.verisign.controller;

import android.util.Log;

import com.bytexcite.verisign.model.entity.RegistrationRequest;
import com.bytexcite.verisign.model.entity.RegistrationResponse;
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
public class RegistrationController {

    private WebServer server = new WebServer();

    public RegistrationController() throws MalformedURLException {
    }

    public HttpRequest<RegistrationResponse> getRegistrationRequest(
            String customerID, SignatureImage[] refSigns) {
        RegistrationRequest r;
        r = new RegistrationRequest(customerID, refSigns[0], refSigns[1], refSigns[2], refSigns[3]);
        return new RegisterRequest(r);
    }

    private class RegisterRequest extends HttpRequest<RegistrationResponse> {

        RegisterRequest(RegistrationRequest request) {
            super(server, "RegistrationController.php", RegistrationResponse.class);
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
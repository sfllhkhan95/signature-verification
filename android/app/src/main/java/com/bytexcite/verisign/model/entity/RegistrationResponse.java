package com.bytexcite.verisign.model.entity;

/**
 * RegistrationResponse is received from the server in response to a RegistrationRequest.
 * It contains status of registration of new user.
 *
 * @author saifkhichi96
 * @version 1.0
 */
public class RegistrationResponse {

    private boolean successful;

    public boolean isSuccessful() {
        return successful;
    }

    public void setSuccessful(boolean successful) {
        successful = successful;
    }

}
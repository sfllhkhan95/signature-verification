package com.bytexcite.verisign.model.entity;

/**
 * VerificationResponse is received from the server in response to a VerificationRequest.
 * It contains results of verification and details of customer to whom the questioned
 * signture belonged.
 *
 * @author saifkhichi96
 * @version 1.0
 */
public class VerificationResponse {

    private boolean genuine;
    private Customer belongsTo;

    public boolean isGenuine() {
        return genuine;
    }

    public void setGenuine(boolean genuine) {
        genuine = genuine;
    }

    public Customer getBelongsTo() {
        return belongsTo;
    }

    public void setBelongsTo(Customer belongsTo) {
        this.belongsTo = belongsTo;
    }
}

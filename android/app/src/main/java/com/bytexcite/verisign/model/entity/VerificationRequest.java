package com.bytexcite.verisign.model.entity;


import java.io.Serializable;

/**
 * VerificationRequest is sent to the server with a customer's identifier and a questioned
 * signature for verification.
 *
 * @author saifkhichi96
 * @version 1.0
 */
public class VerificationRequest implements Serializable {

    private final String customerId;
    private final SignatureImage questionedSignature;

    public VerificationRequest(String customerId, SignatureImage questionedSignature) {
        this.customerId = customerId;
        this.questionedSignature = questionedSignature;
    }

    public String getCustomerId() {
        return customerId;
    }

    public SignatureImage getQuestionedSignature() {
        return questionedSignature;
    }
}

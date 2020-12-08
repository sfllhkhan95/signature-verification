package com.bytexcite.verisign.model.entity;


import java.io.Serializable;

/**
 * RegistrationRequest is sent to the server with a customer's identifier and reference signatures.
 *
 * @author saifkhichi96
 * @version 1.0
 */
public class RegistrationRequest implements Serializable {

    private final String customerId;
    private final SignatureImage refSignA;
    private final SignatureImage refSignB;
    private final SignatureImage refSignC;
    private final SignatureImage refSignD;

    public RegistrationRequest(String customerId, SignatureImage refSignA, SignatureImage refSignB,
                               SignatureImage refSignC, SignatureImage refSignD) {
        this.customerId = customerId;
        this.refSignA = refSignA;
        this.refSignB = refSignB;
        this.refSignC = refSignC;
        this.refSignD = refSignD;
    }

    public String getCustomerId() {
        return customerId;
    }

    public SignatureImage getRefSignA() {
        return refSignA;
    }

    public SignatureImage getRefSignB() {
        return refSignB;
    }

    public SignatureImage getRefSignC() {
        return refSignC;
    }

    public SignatureImage getRefSignD() {
        return refSignD;
    }

}
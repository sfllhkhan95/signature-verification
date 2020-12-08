package com.bytexcite.verisign.model.entity;

import android.support.annotation.Nullable;

import java.io.Serializable;

/**
 * Customer entity represents a customer of the bank.
 *
 * @author saifkhichi96
 * @version 1.0
 */
public class Customer implements Serializable {

    /**
     * id number of the bank customer.
     */
    private String id;

    /**
     * First name of the bank customer.
     */
    private String firstName;

    /**
     * Last name of the bank custormer.
     */
    private String lastName;

    /**
     * Getter method of id.
     *
     * @return id number of the bank customer
     */
    @Nullable
    public String getId() {
        return id;
    }

    /**
     * Sets customer's id number.
     *
     * @param id id number of the bank customer
     */
    public void setId(@Nullable String id) throws IllegalArgumentException {
        if (id == null) return;

        if (id.trim().length() != 13)
            throw new IllegalArgumentException();

        this.id = id.trim();
    }

    /**
     * Gets customer's first name.
     *
     * @return first name of the bank customer
     */
    @Nullable
    public String getFirstName() {
        return firstName;
    }

    /**
     * Sets customer's first name.
     *
     * @param firstName first name of the bank customer
     * @throws IllegalArgumentException exception thrown if name is empty string
     */
    public void setFirstName(@Nullable String firstName) {
        if (firstName == null) return;

        if (firstName.trim().equals(""))
            throw new IllegalArgumentException();

        this.firstName = firstName.trim();
    }

    /**
     * Gets customer's last name.
     *
     * @return last name of the bank customer
     */
    @Nullable
    public String getLastName() {
        return lastName;
    }

    /**
     * Sets customers last name.
     *
     * @param lastName last names of the bank customer
     * @throws IllegalArgumentException exception thrown if name is empty string
     */
    public void setLastName(@Nullable String lastName) throws IllegalArgumentException {
        if (lastName == null) return;

        if (lastName.trim().equals(""))
            throw new IllegalArgumentException();

        this.lastName = lastName.trim();
    }

}

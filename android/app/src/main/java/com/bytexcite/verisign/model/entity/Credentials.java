package com.bytexcite.verisign.model.entity;

import com.bytexcite.verisign.util.WebServer;

/**
 * Credentials entity contains login information of Staff members and is used for authentication
 * with the WebServer.
 *
 * @author saifkhichi96
 * @see WebServer
 * @see Staff
 */
public class Credentials {

    /**
     * Unique username of the Staff member.
     */
    private final String username;

    /**
     * Hashed password of the Staff member.
     */
    private final String password;

    /**
     * Default public constructor.
     *
     * @param username username of Staff memeber
     * @param password password hashed with md5
     */
    public Credentials(String username, String password) throws IllegalArgumentException {
        if (username.trim().length() == 0 || password.trim().length() != 32) {
            throw new IllegalArgumentException();
        }

        this.username = username.trim();
        this.password = password.trim();
    }

    /**
     * Getter method of username.
     *
     * @return username of Staff member
     */
    public String getUsername() {
        return username;
    }

    /**
     * Getter method of password.
     *
     * @return password of Staff member
     */
    public String getPassword() {
        return password;
    }

}

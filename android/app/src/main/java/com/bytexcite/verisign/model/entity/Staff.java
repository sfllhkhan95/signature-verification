package com.bytexcite.verisign.model.entity;


/**
 * Staff is an entity class representing an employee of the bank.
 *
 * @author saifkhichi96
 * @version 1.0
 */
public class Staff {
    private String nic;
    private String username;
    private String password;
    private String firstName;
    private String lastName;
    private String email;
    private String admin;

    public static Staff fromString(String currentUser) {
        String delim = ", ";
        String[] properties = currentUser.split(delim);
        Staff staff = new Staff();
        staff.setNic(properties[0]);
        staff.setUsername(properties[1]);
        staff.setPassword(properties[2]);
        staff.setFirstName(properties[3]);
        staff.setLastName(properties[4]);
        staff.setEmail(properties[5]);
        staff.setAdmin(properties[6]);
        return staff;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getNic() {
        return nic;
    }

    public void setNic(String nic) {
        this.nic = nic;
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username;
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password;
    }

    public String getFirstName() {
        return firstName;
    }

    public void setFirstName(String firstName) {
        this.firstName = firstName;
    }

    public String getLastName() {
        return lastName;
    }

    public void setLastName(String lastName) {
        this.lastName = lastName;
    }

    public boolean isAdmin() {
        return !admin.equals("0");
    }

    public void setAdmin(String admin) {
        this.admin = (admin.equals("0") || admin.toLowerCase().equals("false")) ? "0" : "1";
    }

    @Override
    public String toString() {
        String delim = ", ";
        return nic + delim +
                username + delim +
                password + delim +
                firstName + delim +
                lastName + delim +
                email + delim +
                admin;
    }
}

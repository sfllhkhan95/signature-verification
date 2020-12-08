package com.bytexcite.verisign.model.entity;

import android.app.Activity;
import android.content.Context;
import android.content.SharedPreferences;


/**
 * SessionData is a Singleton containing information of active session.
 */
public class SessionData {
    private static final String PREF_NAME = "_versignSession";
    private static SessionData ourInstance = null;
    private final SharedPreferences preferences;
    private Staff activeUser;

    private SessionData(Activity context) {
        preferences = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
        String currentUser = preferences.getString("currentUser", null);
        if (currentUser != null) {
            this.activeUser = Staff.fromString(currentUser);
        }
    }

    public static SessionData getInstance(Activity context) {
        if (ourInstance == null) {
            ourInstance = new SessionData(context);
        }

        return ourInstance;
    }

    public void createSession(Staff loggedInStaff) {
        this.activeUser = loggedInStaff;
        preferences.edit()
                .putString("currentUser", loggedInStaff.toString())
                .apply();
    }

    public void destroySession() {
        activeUser = null;
        preferences.edit()
                .putString("currentUser", null)
                .apply();
    }

    public Staff getActiveUser() {
        return activeUser;
    }
}
package com.bytexcite.verisign.view;

import android.content.Intent;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.view.View;
import android.view.ViewGroup;
import android.widget.EditText;
import android.widget.Toast;

import com.bytexcite.verisign.R;
import com.bytexcite.verisign.model.dao.StaffDao;
import com.bytexcite.verisign.model.entity.SessionData;
import com.bytexcite.verisign.model.entity.Staff;
import com.bytexcite.verisign.util.HashGenerator;

import java.net.MalformedURLException;

import sfllhkhan95.android.rest.HttpRequest;
import sfllhkhan95.android.rest.ResponseListener;

public class LoginActivity extends AppCompatActivity {

    private EditText username;
    private EditText password;
    private ViewGroup rootView;

    private String credentialsError;
    private String signInFailure;
    private String signInSuccess;

    private StaffDao staffDao;

    private SessionData sessionData;

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        sessionData = SessionData.getInstance(this);

        username = (EditText) findViewById(R.id.username);
        password = (EditText) findViewById(R.id.password);
        rootView = (ViewGroup) findViewById(R.id.activity_login);

        credentialsError = getString(R.string.credentialsError);
        signInFailure = getString(R.string.signInFailure);
        signInSuccess = getString(R.string.signInSuccess);

        try {
            staffDao = new StaffDao();
        } catch (MalformedURLException e) {
            e.printStackTrace();
        }

        LoginController loginController = new LoginController(this);
        findViewById(R.id.loginButton).setOnClickListener(loginController);
    }

    public String getUsername() {
        return username.getText().toString();
    }

    public String getPassword() {
        return password.getText().toString();
    }

    public ViewGroup getRootView() {
        return rootView;
    }

    public void signInSuccess() {
        Toast.makeText(this, signInSuccess, Toast.LENGTH_SHORT).show();

        Intent showMainMenu = new Intent(LoginActivity.this, MenuMainActivity.class);
        Intent launchVerificationScreen = new Intent(LoginActivity.this, VerifySignatureActivity.class);
        startActivity(sessionData.getActiveUser().isAdmin()
                ? showMainMenu
                : launchVerificationScreen);
        finish();
    }

    public void signInFailure() {
        Toast.makeText(this, signInFailure, Toast.LENGTH_SHORT).show();
    }

    public void credentialsError() {
        Toast.makeText(this, credentialsError, Toast.LENGTH_SHORT).show();
    }

    private class LoginController implements View.OnClickListener, ResponseListener<Staff> {

        private final LoginActivity loginActivity;

        LoginController(LoginActivity loginActivity) {
            this.loginActivity = loginActivity;
        }

        @Override
        public void onClick(View v) {
            String u = loginActivity.getUsername();
            String p = loginActivity.getPassword();

            if (isValid(u) && isValid(p)) {
                HttpRequest<Staff> request = staffDao.getFetchRequest(
                        loginActivity.getUsername(),
                        HashGenerator.md5(loginActivity.getPassword())
                );
                request.showStatus(loginActivity.getRootView());
                request.sendRequest(this);
            } else {
                loginActivity.credentialsError();
            }
        }

        private boolean isValid(String string) {
            return string != null && !string.trim().equals("");
        }

        @Override
        public void onRequestSuccessful(Staff staff) {
            sessionData.createSession(staff);
            loginActivity.signInSuccess();
        }

        @Override
        public void onRequestFailed() {
            loginActivity.signInFailure();
        }
    }

}

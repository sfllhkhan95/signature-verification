package com.bytexcite.verisign.view;

import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.view.View;

import com.bytexcite.verisign.R;
import com.bytexcite.verisign.model.entity.SessionData;

public class MenuMainActivity extends AppCompatActivity implements View.OnClickListener {

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_menu_main);

        findViewById(R.id.registerUser).setOnClickListener(this);
        findViewById(R.id.verifySignature).setOnClickListener(this);
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.registerUser:
                startActivity(new Intent(getApplicationContext(), RegisterUserActivity.class));
                overridePendingTransition(0, 0);
                break;

            case R.id.verifySignature:
                startActivity(new Intent(getApplicationContext(), VerifySignatureActivity.class));
                overridePendingTransition(0, 0);
                break;
        }
    }

    public void signOut(View v) {
        SessionData.getInstance(this).destroySession();
        finish();
        startActivity(new Intent(this, LoginActivity.class));
    }

}
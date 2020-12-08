package com.bytexcite.verisign.util;

import java.net.MalformedURLException;

import sfllhkhan95.android.rest.HttpServer;


public class WebServer extends HttpServer {
    private static final String SERVER_ADDRESS = "http://verisign.saifkhichi.com/src/controller/";
    // private static final String SERVER_ADDRESS = "http://192.168.43.223/projects/apps/VerSign/versign-web/src/controller/";

    public WebServer() throws MalformedURLException {
        super(SERVER_ADDRESS);
    }
}
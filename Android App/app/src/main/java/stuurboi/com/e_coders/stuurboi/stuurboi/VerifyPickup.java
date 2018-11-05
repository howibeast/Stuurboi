package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
import android.location.Location;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.mapbox.mapboxsdk.geometry.LatLng;

import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.BlockingQueue;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity;

public class VerifyPickup extends AppCompatActivity {

    public static String UserId ;
    public static LatLng originCoord;
    public static LatLng destinationCoord;
    public static Location currentLocation;
    public static String driverId;
    public static String fromAddress;
    public static String toAddress;
    public static String price;

    String status ="pickedUp";
    String status2 ="cancelled";
    // Creating Progress dialog.
    ProgressDialog progressDialog;
    private String  HttpUrl = "http://stuurboi.000webhostapp.com/users/confirm";
    // Creating Volley RequestQueue.
    RequestQueue requestQueue;
    private BlockingQueue<Boolean> Queue;
    final ExecutorService tpe = Executors.newSingleThreadExecutor();
    private Button button;
    private Button button2;
    private TextView view;
    private String confirmId;

    private Dialog mDialog;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_verify_pickup);
        
        mDialog=new Dialog( VerifyPickup .this);
        mDialog.setContentView(R.layout.request_dialog);
        
        requestQueue = Volley.newRequestQueue(VerifyPickup.this);
        progressDialog = new ProgressDialog(VerifyPickup.this);

        /** Navigate the directions **/
        button = findViewById(R.id.btnConfirmTrip);
        button2=findViewById(R.id.btnCancelTrip);




        view=findViewById(R.id.deliverDecription);

        view.setText(fromAddress);


        button.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                pickup();
            }

        });

        button2.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                cancel();
            }

        });
    }
    public void cancel(){

        // Showing progress dialog at user registration time.
        progressDialog.setMessage("Cancelling Trip");
        progressDialog.show();

        // Creating string request with post method.
        StringRequest stringRequest = new StringRequest(Request.Method.POST, HttpUrl,
                new com.android.volley.Response.Listener<String>() {
                    @Override
                    public void onResponse(String ServerResponse) {
                        // Showing Echo Response Message Coming From Server.
                        TextView name=mDialog.findViewById(R.id.userName);
                        Button cancel=mDialog.findViewById(R.id.btnUser);
                        name.setText("Your trip has been cancelled successfully"+"\n"+"Trip Code : 00"+ServerResponse);
                        cancel.setOnClickListener(new View.OnClickListener() {
                            public void onClick(View v) {
                                Intent intent = new Intent(VerifyPickup.this, DriverTabActivity.class);
                                startActivity(intent);
                            }

                        });
                        mDialog.show();
                    }
                },
                new com.android.volley.Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {
                        // Hiding the progress dialog after all task complete.
                        progressDialog.dismiss();

                        // Showing error message if something goes wrong.
                        Toast.makeText(VerifyPickup.this, volleyError.toString(), Toast.LENGTH_LONG).show();
                    }
                }) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                HashMap<String, String> headers = new HashMap<String, String>();
                headers.put("X-API-KEY", "CODEX@123");
                return headers;
            }
            @Override
            protected Map<String, String> getParams() {
                // Creating Map String Params.
                Map<String, String> params = new HashMap<String, String>();
                params.put("driverId",driverId);
                params.put("pickupLocation",fromAddress);
                params.put("destinationLocation",toAddress);
                params.put("confirmPickup",status2);
                return params;
            }
        };
        // Creating RequestQueue.
        RequestQueue requestQueue = Volley.newRequestQueue(VerifyPickup.this);

        // Adding the StringRequest object into requestQueue.
        requestQueue.add(stringRequest);
    }


    public void pickup(){

        // Showing progress dialog at user registration time.
        progressDialog.setMessage("Please Wait");
        progressDialog.show();

        // Creating string request with post method.
        StringRequest stringRequest = new StringRequest(Request.Method.POST, HttpUrl,
                new com.android.volley.Response.Listener<String>() {
                    @Override
                    public void onResponse(String ServerResponse) {
                        // Showing Echo Response Message Coming From Server.
                        confirmId = ServerResponse;
                        Delivery.userId=confirmId;

                        Toast.makeText(VerifyPickup.this, "have fun", Toast.LENGTH_LONG).show();
                        Intent intent = new Intent(VerifyPickup.this, Delivery.class);
                        Delivery.originCoord=originCoord;
                        Delivery.destinationCoord=destinationCoord;
                        Delivery.currentLocation=currentLocation;
                        Delivery.fromAddress=fromAddress;
                        Delivery.toAddress=toAddress;
                        Delivery.UserId=UserId; // requesting user
                        Delivery.driverId=driverId;
                        Delivery.price=price;

                        startActivity(intent);
                        finish();

                    }
                },
                new com.android.volley.Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {
                        // Hiding the progress dialog after all task complete.
                        progressDialog.dismiss();

                        // Showing error message if something goes wrong.
                        Toast.makeText(VerifyPickup.this, volleyError.toString(), Toast.LENGTH_LONG).show();
                    }
                }) {
            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                HashMap<String, String> headers = new HashMap<String, String>();
                headers.put("X-API-KEY", "CODEX@123");
                return headers;
            }
            @Override
            protected Map<String, String> getParams() {
                // Creating Map String Params.
                Map<String, String> params = new HashMap<String, String>();
                params.put("driverId",driverId);
                params.put("pickupLocation",fromAddress);
                params.put("destinationLocation",toAddress);
                params.put("confirmPickup",status);
                return params;
            }
        };
        // Creating RequestQueue.
        RequestQueue requestQueue = Volley.newRequestQueue(VerifyPickup.this);

        // Adding the StringRequest object into requestQueue.
        requestQueue.add(stringRequest);
    }
}

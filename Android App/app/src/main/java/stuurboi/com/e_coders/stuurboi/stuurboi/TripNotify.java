package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.app.Dialog;
import android.app.ProgressDialog;
import android.content.Intent;
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
import com.mapbox.mapboxsdk.Mapbox;

import java.text.DecimalFormat;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;
import stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity;

public class TripNotify extends AppCompatActivity {

    public static String requestId;
    public static String fromAddress;
    public static String toAddress;
    public static String UserId;
    public static String driverId;
    public static String userId;
    public static String price;

    public static Double distance;
    public static Double duration;
    public static Double dblPrice;

    // public static int requestId;
    private TextView view;

    // Creating Progress dialog.
    ProgressDialog progressDialog;
    static String  HttpUrl = "http://stuurboi.000webhostapp.com/users/update";
    // Creating Volley RequestQueue.
    RequestQueue requestQueue;
    String status ="delivered";
    String status2 ="noReceiver";

    final static ExecutorService tpe = Executors.newSingleThreadExecutor();
    private Dialog mDialog;
    private Button button;
    private Button button2;
    // private TextView view;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_trip_notify);

        view=findViewById(R.id.destinationLocation);
        button=findViewById(R.id.btnConfirmDelivery);
        button2=findViewById(R.id.btnReportTrip);

        mDialog=new Dialog( TripNotify .this);
        mDialog.setContentView(R.layout.request_dialog2);

        view.setText(toAddress);

        requestQueue = Volley.newRequestQueue(TripNotify.this);
        progressDialog = new ProgressDialog(TripNotify.this);


        String[] string=price.split(": R");
        dblPrice=Double.valueOf(string[1]);

        button.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {

                deliever();
            }

        });

        button2.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {
                cancel();
            }

        });
    }

    public void deliever(){
        // Showing progress dialog at user registration time.
        progressDialog.setMessage("Please Wait");
        progressDialog.show();

        // Creating string request with post method.
        StringRequest stringRequest = new StringRequest(Request.Method.POST, HttpUrl,
                new com.android.volley.Response.Listener<String>() {
                    @Override
                    public void onResponse(String ServerResponse) {
                        if(ServerResponse.equalsIgnoreCase("successfully updated")) {

                            // If response matched then show the toast.
                            Toast.makeText(TripNotify.this, "Delivery Confirmed", Toast.LENGTH_LONG).show();

                            Intent intent = new Intent(TripNotify.this, TripInfo.class);

                            TripInfo.fromAddress=fromAddress;
                            TripInfo.toAddress=toAddress;
                            TripInfo.UserId=UserId; // requesting user
                            TripInfo.driverId=driverId;
                            TripInfo.price=price;


                            duration=duration/60;

                            distance=distance/(1.60934*1000);
                            distance=roundTwoDecimals(distance);


                            duration=roundTwoDecimals(duration);

                            TripInfo.duration=duration;
                            TripInfo.distance=distance;

                            tpe.submit(new Runnable() {
                                @Override
                                public void run() {
                                    trips();
                                }

                            });
                            startActivity(intent);
                            finish();
                        }
                        else {
                            // Showing Echo Response Message Coming From Server.
                            Toast.makeText(TripNotify.this, ServerResponse, Toast.LENGTH_LONG).show();
                        }
                    }
                },
                new com.android.volley.Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {
                        // Hiding the progress dialog after all task complete.
                        progressDialog.dismiss();

                        // Showing error message if something goes wrong.
                        Toast.makeText(TripNotify.this, volleyError.toString(), Toast.LENGTH_LONG).show();
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


                params.put("userId",userId);
                params.put("confirmDelivery",status2);
                //params.put("clientId",clientId);

                return params;
            }

        };

        // Creating RequestQueue.
        RequestQueue requestQueue = Volley.newRequestQueue(TripNotify.this);

        // Adding the StringRequest object into requestQueue.
        requestQueue.add(stringRequest);
    }
    double roundTwoDecimals(double d)
    {
        DecimalFormat twoDForm = new DecimalFormat("#.##");
        return Double.valueOf(twoDForm.format(d));
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

                        TextView name = mDialog.findViewById(R.id.userName2);
                        Button cancel = mDialog.findViewById(R.id.btnUser2);

                        if (ServerResponse.equalsIgnoreCase("successfully updated")) {
                            name.setText("Your client has been reported successfully " + "\n" + "status : Return the goods");
                            cancel.setOnClickListener(new View.OnClickListener() {
                                public void onClick(View v) {
                                    Intent intent = new Intent(TripNotify.this, DriverTabActivity.class);
                                    startActivity(intent);
                                }

                            });
                            mDialog.show();
                        } else {
                            // Showing Echo Response Message Coming From Server.
                            name.setText(ServerResponse);
                            mDialog.show();
                        }
                    }
                },
                new com.android.volley.Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {
                        // Hiding the progress dialog after all task complete.
                        progressDialog.dismiss();

                        // Showing error message if something goes wrong.
                        Toast.makeText(TripNotify.this, volleyError.toString(), Toast.LENGTH_LONG).show();
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
                params.put("userId",userId);
                params.put("confirmDelivery",status);
                return params;
            }
        };
        // Creating RequestQueue.
        RequestQueue requestQueue = Volley.newRequestQueue(TripNotify.this);

        // Adding the StringRequest object into requestQueue.
        requestQueue.add(stringRequest);
    }









    static String  HttpUrl2 = "http://stuurboi.000webhostapp.com/users/trips";
    public void trips(){

        // Creating string request with post method.
        StringRequest stringRequest = new StringRequest(Request.Method.POST, HttpUrl2,
                new com.android.volley.Response.Listener<String>() {
                    @Override
                    public void onResponse(String ServerResponse) {
                        if(ServerResponse.equalsIgnoreCase("successfully updated")) {

                            // If response matched then show the toast.
                            Toast.makeText(TripNotify.this, "Trip Confirmed", Toast.LENGTH_LONG).show();


                            finish();
                        }
                        else {
                            // Showing Echo Response Message Coming From Server.
                            Toast.makeText(TripNotify.this, ServerResponse, Toast.LENGTH_LONG).show();
                        }
                    }
                },
                new com.android.volley.Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {
                        // Hiding the progress dialog after all task complete.
                        progressDialog.dismiss();

                        // Showing error message if something goes wrong.
                        Toast.makeText(TripNotify.this, volleyError.toString(), Toast.LENGTH_LONG).show();
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
                params.put("requestId",userId);
                params.put("pickupLocation",fromAddress);
                params.put("destinationLocation",toAddress);
                params.put("duration",String.valueOf(duration));
                params.put("mileage",String.valueOf(distance));
                params.put("fare",String.valueOf( dblPrice));
                params.put("status","completed");
                return params;
            }

        };

        // Creating RequestQueue.
        RequestQueue requestQueue = Volley.newRequestQueue(TripNotify.this);

        // Adding the StringRequest object into requestQueue.
        requestQueue.add(stringRequest);
    }
}


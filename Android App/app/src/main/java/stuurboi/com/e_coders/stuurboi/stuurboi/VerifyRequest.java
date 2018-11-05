package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.location.Address;
import android.location.Geocoder;
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

import java.io.IOException;
import java.text.DecimalFormat;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.Locations;

import static stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity.driverId;

public class VerifyRequest extends AppCompatActivity {

    public static String price;
    public static String userId;
    public static String to;
    public static String from;
    public static String receiverName;
    public static String receiverCell;


    private TextView view1;
    private Button button;

    private LatLng destinationCoord;
    private LatLng pickUpCoord;


    private Locations location;
    // Creating Progress dialog.
    ProgressDialog progressDialog;
    static String  HttpUrl = "http://stuurboi.000webhostapp.com/users/requestStatus";
    // Creating Volley RequestQueue.
    private RequestQueue requestQueue;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_verify_request);

        requestQueue = Volley.newRequestQueue(VerifyRequest.this);
        progressDialog = new ProgressDialog(VerifyRequest.this);

        location=new Locations();

        view1=findViewById(R.id.userName);
        button=findViewById(R.id.btnOk);

        view1.setText("   Name : "+receiverName+"\n"+
                "   Cell : "+receiverCell+"\n"
        );

       button.setOnClickListener(new View.OnClickListener() {


           @Override
            public void onClick(View v) {
                LatLng pickup=getLocationFromAddress(VerifyRequest.this,from);
                setPickup(pickup);

                LatLng deliver=getLocationFromAddress(VerifyRequest.this,to);
                setDeliver(deliver);

                PickupNavigation.destinationCoord=getPickUpCoord();
               PickupNavigation.tempCoord=getDestinationCoord();

               PickupNavigation.fromAddress=getFrom();
               PickupNavigation.toAddress=getTo();

               PickupNavigation.driverId=driverId;  // for the logged in driver
               PickupNavigation.userId=userId;   // for the requesting user

               PickupNavigation.price=price;

                //activate the status in the database
                requestStatus();
            }
        });
        requestQueue = Volley.newRequestQueue(this);

    }

    double roundTwoDecimals(double d)
    {
        DecimalFormat twoDForm = new DecimalFormat("#.##");
        return Double.valueOf(twoDForm.format(d));
    }


    public void setPickup(LatLng pickup){
        double Latitude=pickup.getLatitude();
        double Longitude=pickup.getLongitude();

        LatLng destinationCoord=new LatLng(Latitude, Longitude);
        this.setPickUpCoord(destinationCoord);

    }

    public void setDeliver(LatLng deliver){
        double Latitude=deliver.getLatitude();
        double Longitude=deliver.getLongitude();

        LatLng OriginCoord=new LatLng(Latitude, Longitude);
        this.setDestinationCoord(OriginCoord);

    }
    /**********************************************************
     * Function to get coordinates from a string
     * @param context
     * @param strAddress
     * @return
     *********************************************************/
    public LatLng getLocationFromAddress(Context context, String strAddress) {

        Geocoder coder = new Geocoder(context);
        List<Address> address;
        LatLng p1 = null;

        try {
            // May throw an IOException
            address = coder.getFromLocationName(strAddress, 5);
            if (address == null) {
                return null;
            }

            Address location = address.get(0);
            p1 = new LatLng(location.getLatitude(), location.getLongitude() );

        } catch (IOException ex) {

            ex.printStackTrace();
        }

        return p1;
    }

    /*******************************************************************
     * GETTERS and SETTERS
     */
    public void setPickUpCoord(LatLng pickUpCoord) {
        this.pickUpCoord = pickUpCoord;
        location.setOriginCoord(this.pickUpCoord);
    }

    public String getFrom() {
        return from;
    }

    public void setFrom(String from) {
        this.from = from;
    }

    public String getTo() {
        return to;
    }

    public void setTo(String to) {
        this.to = to;
    }

    public void setDestinationCoord(LatLng destinationCoord) {
        this.destinationCoord = destinationCoord;
        location.setDestinationCoord(this.destinationCoord);
    }

    public LatLng getPickUpCoord() {
        return location.getOriginCoord();
    }
    public LatLng getDestinationCoord() {
        return location.getDestinationCoord();
    }

    /***
     * Activate the status in the database
     */



    public void requestStatus(){

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
                            Toast.makeText(VerifyRequest.this, "Route Found", Toast.LENGTH_LONG).show();

                            Intent intent = new Intent(VerifyRequest.this, PickupNavigation.class);
                            startActivity(intent);
                            //Pickup pickups =new Pickup();
                            // Finish the current Login activity.
                            finish();
                        }
                        else {
                            // Showing Echo Response Message Coming From Server.
                            Toast.makeText(VerifyRequest.this, ServerResponse, Toast.LENGTH_LONG).show();
                        }
                    }
                },
                new com.android.volley.Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {
                        // Hiding the progress dialog after all task complete.
                        progressDialog.dismiss();

                        // Showing error message if something goes wrong.
                        Toast.makeText(VerifyRequest.this, volleyError.toString(), Toast.LENGTH_LONG).show();
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
                params.put("id",userId);
                params.put("driverId",driverId);
                params.put("status","accepted");
                return params;
            }
        };
        // Creating RequestQueue.
        com.android.volley.RequestQueue requestQueue = Volley.newRequestQueue(VerifyRequest.this);

        // Adding the StringRequest object into requestQueue.
        requestQueue.add(stringRequest);
    }
}

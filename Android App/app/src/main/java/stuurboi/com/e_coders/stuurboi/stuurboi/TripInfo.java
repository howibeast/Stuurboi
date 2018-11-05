package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.RatingBar;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import java.text.DecimalFormat;
import java.util.HashMap;
import java.util.Map;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity;

import static stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity.driverId;

public class TripInfo extends AppCompatActivity {


    public static Double duration;
    public static Double distance;

    public static String fromAddress;
    public static String toAddress;
    public static String UserId;
    public static String driverId;
    public static String price;

    TextView view1;
    Button button;
    public RatingBar ratingBar;
    float rate;


    // Creating Progress dialog.
    ProgressDialog progressDialog;
    private String  HttpUrl = "http://stuurboi.000webhostapp.com/users/rateUser";
    // Creating Volley RequestQueue.
    RequestQueue requestQueue;
    final static ExecutorService tpe = Executors.newSingleThreadExecutor();


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_trip_info);


        view1=findViewById(R.id.pickUpInfo);
        // Initialize RatingBar
        ratingBar = (RatingBar) findViewById(R.id.ratingBar);

        requestQueue = Volley.newRequestQueue(TripInfo.this);
        progressDialog = new ProgressDialog(TripInfo.this);


        String string2=String.valueOf(price);
        String nwString = string2.replace("Price:", "");

        String string=String.valueOf(duration);
        String newString = string.replace(".", "m");
        String substring=newString.substring(0,5);

        view1.setText("From "+fromAddress+" To  "+toAddress+"\n\n"+
                      "Price : "+nwString +"\n"+
                       "Duration : "+substring+"s"+"\n"+
                       "Distance : "+String.valueOf(distance)+" miles"+"\n");

        button=findViewById(R.id.moreRequests);

        button.setOnClickListener(new View.OnClickListener() {
            public void onClick(View v) {

                Intent intent = new Intent(TripInfo.this, DriverTabActivity.class);
                // get values and then displayed in a toast
                String totalStars = "Total Stars:: " +ratingBar.getNumStars();
                String rating= "Rating :: " + ratingBar.getRating();
                Toast.makeText(getApplicationContext(), totalStars + "\n" + rating, Toast.LENGTH_LONG).show();

                    rate=ratingBar.getRating();
                    rate();
                    /**tpe.submit(new Runnable() {
                        @Override
                        public void run() { rate(); }
                    });**/

            }

        });

    }


    /**
     * Display rating by calling getRating() method.
     * @param view
     */
    public void rateMe(View view){

        Intent intent = new Intent(TripInfo.this, DriverTabActivity.class);

        String totalStars = "Total Stars:: " +ratingBar.getNumStars();
        String rating= "Rating :: " + ratingBar.getRating();
        Toast.makeText(getApplicationContext(), totalStars + "\n" + rating, Toast.LENGTH_LONG).show();
    }

    public void rate(){

        // Showing progress dialog at user registration time.
        progressDialog.setMessage("Rating Your Client");
        progressDialog.show();

        // Creating string request with post method.
        StringRequest stringRequest = new StringRequest(Request.Method.POST, HttpUrl,
                new com.android.volley.Response.Listener<String>() {
                    @Override
                    public void onResponse(String ServerResponse) {
                        // Showing Echo Response Message Coming From Server.

                        Toast.makeText(TripInfo.this, ServerResponse, Toast.LENGTH_LONG).show();
                        Intent intent = new Intent(TripInfo.this, DriverTabActivity.class);

                        DriverTabActivity.UserId=UserId; // requesting user
                        DriverTabActivity.driverId=driverId;

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
                        Toast.makeText(TripInfo.this, volleyError.toString(), Toast.LENGTH_LONG).show();
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
                params.put("id",UserId);
                params.put("ratings",String.valueOf(ratingBar.getRating()));
                return params;
            }
        };
        // Creating RequestQueue.
        RequestQueue requestQueue = Volley.newRequestQueue(TripInfo.this);

        // Adding the StringRequest object into requestQueue.
        requestQueue.add(stringRequest);
    }
}

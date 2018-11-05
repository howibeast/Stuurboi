package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.app.ProgressDialog;
import android.content.Intent;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.github.nkzawa.socketio.client.IO;
import com.github.nkzawa.socketio.client.Socket;

import org.json.JSONObject;

import java.net.URISyntaxException;
import java.util.HashMap;
import java.util.Map;

import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.SingletonRequestQueue;
import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.User;
import stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity;


public class SignIn extends AppCompatActivity {

    // Creating EditText.
    EditText Email, Password;
    // Creating button;
    Button LoginButton;
    // Creating Volley RequestQueue.
    RequestQueue requestQueue;
    // Create string variable to hold the EditText Value.
    String EmailHolder, PasswordHolder;
    // Creating Progress dialog.
    ProgressDialog progressDialog;
    // Storing server url into String variable.
    String HttpUrl = "http://stuurboi.000webhostapp.com/users/login";
    Boolean CheckEditText;
    User user;
    String BASE_URL = "http://stuurboi.000webhostapp.com/api/users/signin";
    String status;
    String userId;
    String message;
    String userType;
    String name;
    String surname;
    String gender;
    String email;
    String cellNumber;
    String password;
    String dateCreated;
    private Socket socket;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_sign_in);

        user=new User();
        // Assigning ID's to EditText.
        Email = (EditText) findViewById(R.id.editTextEmail);

        Password = (EditText) findViewById(R.id.editText3);

        // Assigning ID's to Button.
        LoginButton = (Button) findViewById(R.id.btnSignIn);

        // Creating Volley newRequestQueue .
        requestQueue = Volley.newRequestQueue(SignIn.this);

        // Assigning Activity this to progress dialog.
        progressDialog = new ProgressDialog(SignIn.this);

        // Adding click listener to button.
        LoginButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                CheckEditTextIsEmptyOrNot();

                if (CheckEditText) {

                    GETStringAndJSONRequest();


                } else {

                    Toast.makeText(SignIn.this, "Please fill all form fields.", Toast.LENGTH_LONG).show();

                }

            }
        });

        //If user presses on not registered
        findViewById(R.id.txtWebLinkRegister).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                //open register screen
                finish();
                startActivity(new Intent(getApplicationContext(), Signup.class));
            }
        });

    }




    private void GETStringAndJSONRequest() {
        // Showing progress dialog at user registration time.
        progressDialog.setMessage("Please Wait");
        progressDialog.show();
        RequestQueue queue = SingletonRequestQueue.getInstance(getApplicationContext()).getRequestQueue();


        StringRequest strRequest = new StringRequest(Request.Method.POST, BASE_URL, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                // Hiding the progress dialog after all task complete.
                progressDialog.dismiss();

                try{
                    final JSONObject businessObject = new JSONObject(response);
                    if (businessObject.length() > 0)
                    {
                        status = businessObject.optString("status");
                        Log.e("TAG", "status:" + status);

                        JSONObject jsonObject = businessObject.getJSONObject("user");
                        if (jsonObject.length() > 0) {
                            userId = jsonObject.optString("id");    //get also get profile data here
                            Log.e("TAG", "userId:" + userId);

                            userType = jsonObject.optString("userType");
                            Log.e("TAG", "userType:" + userType);
                        }

                        message=businessObject.optString("message");
                        Log.e("TAG", "message:"+ message);

                        String  status2= jsonObject.getJSONObject(response).getJSONObject("user").getString("status");
                    }
                }catch (Exception e) {
                    e.printStackTrace();
                }
                //converting the string to json array object


                if(status.equals("true")) {
                    // If response matched then show the toast.
                    Toast.makeText(SignIn.this, message, Toast.LENGTH_LONG).show();

                    // Finish the current Login activity.
                    finish();

                    if(userType.equals("driver")) {

                        // Opening the user profile activity using intent.
                        Intent intent = new Intent(SignIn.this, DriverTabActivity.class);
                        DriverTabActivity.driverId=userId;

                        // Sending User Email to another activity using intent.
                        intent.putExtra("UserEmailTAG", EmailHolder);
                        startActivity(intent);

                    }else if(userType.equals("client")){

                        // Opening the user profile activity using intent.
                        Intent intent = new Intent(SignIn.this, ClientTabActivity.class);
                        ClientTabActivity.userId=userId;

                        // Sending User Email to another activity using intent.
                        intent.putExtra("UserEmailTAG", EmailHolder);
                        startActivity(intent);
                    }
                }else{
                    // If response matched then show the toast.
                    Toast.makeText(SignIn.this, message, Toast.LENGTH_LONG).show();

                    // Finish the current Login activity.
                    finish();

                }
            }


        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError e) {
                // Hiding the progress dialog after all task complete.
                progressDialog.dismiss();

                // Showing error message if something goes wrong.
                VolleyError error=parseNetworkError(e);
                Toast.makeText(SignIn.this, error.toString(), Toast.LENGTH_LONG).show();
            }
        }) {
            @Override
            public Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();


                params.put("email", EmailHolder);
                params.put("password", PasswordHolder);


                return params;
            }
        };
        queue.add(strRequest);
    }

    //In your extended request class
    protected VolleyError parseNetworkError(VolleyError volleyError){
        if(volleyError.networkResponse != null && volleyError.networkResponse.data != null){
            VolleyError error = new VolleyError(new String(volleyError.networkResponse.data));
            volleyError = error;
        }

        return volleyError;
    }


    public void CheckEditTextIsEmptyOrNot() {

        // Getting values from EditText.
        EmailHolder = Email.getText().toString().trim();
        PasswordHolder = Password.getText().toString().trim();

        // Checking whether EditText value is empty or not.
        if (TextUtils.isEmpty(EmailHolder) || TextUtils.isEmpty(PasswordHolder)) {

            // If any of EditText is empty then set variable value as False.
            CheckEditText = false;

        } else {

            // If any of EditText is filled then set variable value as True.
            CheckEditText = true;
        }
    }
}


package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.text.TextUtils;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.SingletonRequestQueue;

public class Signup extends AppCompatActivity {

    EditText txtName, txtSurname,txtEmail,txtPhone, txtPassword;
    RadioGroup radioGroupGender,radioGroupUser;
    private RadioButton radioSexButton;
    private RadioButton radioUserButton;
    ProgressBar progressBar;

    // Creating button;
    Button Register;

    // Creating Volley RequestQueue.
    RequestQueue requestQueue;

    // Create string variable to hold the EditText Value.
    String NameHolder, SurnameHolder, EmailHolder, cellPhoneHolder,genderHolder,userHolder,PasswordHolder ;

    // Creating Progress dialog.
    ProgressDialog progressDialog;
    Boolean CheckEditText ;
    String BASE_URL = "http://stuurboi.000webhostapp.com/api/users/signup";


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_signup);


        txtName = (EditText) findViewById(R.id.txtTextName);
        txtSurname = findViewById(R.id.txtTextSurname);
        txtEmail = (EditText) findViewById(R.id.txtTextEmail);
        txtPhone = findViewById(R.id.txtTextPhone);
        txtPassword = (EditText) findViewById(R.id.txtTextPassword);

        radioGroupGender = (RadioGroup) findViewById(R.id.radioGender);
        radioGroupUser = findViewById(R.id.radioUser);

        Register = (Button) findViewById(R.id.btnRegister);
        requestQueue = Volley.newRequestQueue(Signup.this);
        progressDialog = new ProgressDialog(Signup.this);

        //   Adding click listener to button.
        Register.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                // get selected radio button from radioGroup
                int selectedId = radioGroupGender.getCheckedRadioButtonId();
                int selectedId2=radioGroupUser.getCheckedRadioButtonId();

                // find the radiobutton by returned id
                radioSexButton = (RadioButton) findViewById(selectedId);
                radioUserButton=findViewById(selectedId2);

                genderHolder=radioSexButton.getText().toString();
                userHolder=radioUserButton.getText().toString();

                /**Toast.makeText(Registration.this,
                 radioSexButton.getText(), Toast.LENGTH_SHORT).show();**/


                CheckEditTextIsEmptyOrNot();

                if(CheckEditText){

                    UserRegistration();

                }
                else {

                    Toast.makeText(Signup.this, "Please fill all form fields.", Toast.LENGTH_LONG).show();

                }


            }
        });

        findViewById(R.id.textViewLogin).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                //if user pressed on login
                //we will open the login screen
                finish();
                startActivity(new Intent(Signup.this, SignIn.class));
            }
        });

    }

    public void CheckEditTextIsEmptyOrNot() {

        // Getting values from EditText.
        NameHolder = txtName.getText().toString().trim();
        SurnameHolder = txtSurname.getText().toString().trim();
        EmailHolder = txtEmail.getText().toString().trim();
        cellPhoneHolder = txtPhone.getText().toString().trim();
        PasswordHolder = txtPassword.getText().toString().trim();

        // Checking whether EditText value is empty or not.
        if (TextUtils.isEmpty(NameHolder) || TextUtils.isEmpty(EmailHolder) || TextUtils.isEmpty(PasswordHolder)) {

            // If any of EditText is empty then set variable value as False.
            CheckEditText = false;

        } else {

            // If any of EditText is filled then set variable value as True.
            CheckEditText = true;
        }
    }

    String status;
    String userId;
    String message;
    private final String TAG = "signup";
    // Creating user login function.
    public void UserRegistration() {

        // Showing progress dialog at user registration time.
        progressDialog.setMessage("Please Wait");
        progressDialog.show();

        // Creating string request with post method.
        RequestQueue queue = SingletonRequestQueue.getInstance(getApplicationContext()).getRequestQueue();


        StringRequest strRequest = new StringRequest(Request.Method.POST, BASE_URL, new Response.Listener<String>()  {
            @Override
            public void onResponse(String ServerResponse) {

                // Hiding the progress dialog after all task complete.
                progressDialog.dismiss();

                try{
                    final JSONObject jsonObject = new JSONObject(ServerResponse);
                    if (jsonObject.length() > 0)
                    {
                        status=jsonObject.optString("status");
                        Log.e("TAG", "status:"+ status);

                        userId= jsonObject.optString("userId");
                        Log.e("TAG", "userId:"+ userId);

                        message=jsonObject.optString("message");
                        Log.e("TAG", "message:"+ message);
                    }
                }catch (Exception e) {
                    e.printStackTrace();
                }
                //converting the string to json array object


                if(status.equalsIgnoreCase("true")) {
                    // If response matched then show the toast.
                    Toast.makeText(Signup.this, message, Toast.LENGTH_LONG).show();

                    // Finish the current Login activity.
                    finish();

                    // Opening the user profile activity using intent.
                    Intent intent = new Intent(Signup.this, SignIn.class);

                    // Sending User Email to another activity using intent.
                    intent.putExtra("UserEmailTAG", EmailHolder);
                    startActivity(intent);
                }else{
                    // If response matched then show the toast.
                    Toast.makeText(Signup.this,  message, Toast.LENGTH_LONG).show();

                    // Finish the current Login activity.
                    finish();

                }
            }

        },
                new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {

                        // Hiding the progress dialog after all task complete.
                        progressDialog.dismiss();

                        // Showing error message if something goes wrong.
                        Toast.makeText(Signup.this, volleyError.toString(), Toast.LENGTH_LONG).show();
                    }
                })/** {
        @Override
        public Map<String, String> getHeaders() throws AuthFailureError {
        HashMap<String, String> headers = new HashMap<String, String>();
        headers.put("X-API-KEY", "CODEX@123");
        return headers;
        }**/
        {
            @Override
            public Map<String, String> getParams() {
                Map<String, String> params = new HashMap<>();
                // Adding All values to Params.
                // The firs argument should be same sa your MySQL database table columns.
                //PasswordHolder=MD5_Hash(PasswordHolder);
                params.put("name", NameHolder);
                params.put("surname", SurnameHolder);
                params.put("email", EmailHolder);
                params.put("cellNumber", cellPhoneHolder);
                params.put("gender", genderHolder);
                params.put("userType", userHolder);
                params.put("password", PasswordHolder);
                params.put("avatar", "docs/newUser/image.png");

                return params;
            }

        };

        queue.add(strRequest);
    }
}

package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import com.mapbox.mapboxsdk.geometry.LatLng;

import stuurboi.com.e_coders.stuurboi.stuurboi.R;
import stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity;

import static stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity.driverId;

public class VerifyTrip extends AppCompatActivity {

    public static String from;
    public static String to;
    public static String mileage;
    public static String fare;

    private TextView view1;
    private Button button;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_verify_trip);

        view1=findViewById(R.id.userTrips);
        button=findViewById(R.id.btnCool);

        view1.setText("   From : "+from+"\n"+
                "   To: "+to+"\n"+
                "   Fare: "+fare+"\n"+
                "   Mileage: "+mileage+"\n"
        );

        button.setOnClickListener(new View.OnClickListener() {


            @Override
            public void onClick(View v) {
                Intent intent = new Intent(VerifyTrip.this, DriverTabActivity.class);
                startActivity(intent);
            }
        });
    }
}

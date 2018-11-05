package stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager;

import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.TabLayout;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;

import stuurboi.com.e_coders.stuurboi.stuurboi.PickupNavigation;
import stuurboi.com.e_coders.stuurboi.stuurboi.Fragment.TripsFragment;
import stuurboi.com.e_coders.stuurboi.stuurboi.Fragment.RequestsFragment;
import stuurboi.com.e_coders.stuurboi.stuurboi.Fragment.RatingsFragment;
import stuurboi.com.e_coders.stuurboi.stuurboi.R;
import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.ViewPagerAdapter;
import stuurboi.com.e_coders.stuurboi.stuurboi.VerifyPickup;
import stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverPager.TabWOIconActivity;

public class DriverTabActivity extends AppCompatActivity {

    public static String UserId ;
    public static String driverId;
    public static int count;
    //This is our tablayout
    private TabLayout tabLayout;

    //This is our viewPager
    private ViewPager viewPager;

    //Fragments
    RequestsFragment requestsFragment;
    TripsFragment tripsFragment;
    RatingsFragment ratingsFragment;

    String[] tabTitle={"Trips","Reque.","Ratings"};
    int[] unreadCount={0,0,0};

    private final String TAG = "viewRequests";
    JSONArray array = null;

    @Override
    protected void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tab_icon_less);

       setArray(unreadCount);

        PickupNavigation.driverId=driverId;

        RequestQueue queue = Volley.newRequestQueue(DriverTabActivity.this);
        String url ="http://stuurboi.000webhostapp.com/users/getRequests";

        //Initializing viewPager
        viewPager = (ViewPager) findViewById(R.id.viewpager);
        viewPager.setOffscreenPageLimit(3);
        setupViewPager(viewPager);

        //Initializing the tablayout
        tabLayout = (TabLayout) findViewById(R.id.tablayout);
        tabLayout.setupWithViewPager(viewPager);

        try
        {
            setupTabIcons();
        }
        catch (Exception e)
        {
            e.printStackTrace();
        }

        viewPager.addOnPageChangeListener(new ViewPager.OnPageChangeListener() {
            @Override
            public void onPageScrolled(int position, float positionOffset, int positionOffsetPixels) {

            }

            @Override
            public void onPageSelected(int position) {
                viewPager.setCurrentItem(position,false);

            }

            @Override
            public void onPageScrollStateChanged(int state) {

            }
        });
    }

    public void setArray( int[] unreadCount2){

        RequestQueue queue = Volley.newRequestQueue(DriverTabActivity.this);
        String url ="http://stuurboi.000webhostapp.com/users/getRequests";

        StringRequest stringRequest = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.d(TAG, "Response " + response);
                try {
                    array = new JSONArray(response);

                    if(array.length()==0){
                        count=1;
                    }else{
                        count=Integer.valueOf(array.length());
                        unreadCount2[1]= count;
                        count=unreadCount2[1];
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.d(TAG, "Error " + error.getMessage());
            }
        });

        unreadCount2[1]= count;
        queue.add(stringRequest);
    }

    @Override
    public boolean onCreateOptionsMenu(final Menu menu) {

        getMenuInflater().inflate(R.menu.menu_home, menu);
        // Associate searchable configuration with the SearchView
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle item selection
        switch (item.getItemId()) {
            case R.id.action_status:
                Toast.makeText(this, "Home Status Click", Toast.LENGTH_SHORT).show();
                return true;
            case R.id.action_settings:
                Toast.makeText(this, "Home Settings Click", Toast.LENGTH_SHORT).show();
                return true;
            case R.id.action_with_icon:
                Intent withicon=new Intent(this,TabWOIconActivity.class);
                startActivity(withicon);
                finish();
                return true;

            default:
                return super.onOptionsItemSelected(item);
        }
    }

    private void setupViewPager(ViewPager viewPager)
    {
        ViewPagerAdapter adapter = new ViewPagerAdapter(getSupportFragmentManager());
        tripsFragment =new TripsFragment();
        requestsFragment =new RequestsFragment();
        ratingsFragment =new RatingsFragment();
        adapter.addFragment(tripsFragment,"Trips");
        adapter.addFragment(requestsFragment,"Requests");
        adapter.addFragment(ratingsFragment,"Ratings");
        viewPager.setAdapter(adapter);
    }

    private View prepareTabView(int pos) {

        View view = getLayoutInflater().inflate(R.layout.activity_driver_slider,null);
        TextView tv_title = (TextView) view.findViewById(R.id.tv_title);
        TextView tv_count = (TextView) view.findViewById(R.id.tv_count);
        tv_title.setText(tabTitle[pos]);
        if(unreadCount[pos]>0)
        {
            tv_count.setVisibility(View.VISIBLE);
            tv_count.setText(""+unreadCount[pos]);
        }
        else
            tv_count.setVisibility(View.GONE);


        return view;
    }

    private void setupTabIcons()
    {
        for(int i=0;i<tabTitle.length;i++)
        {
            tabLayout.getTabAt(i).setCustomView(prepareTabView(i));
        }


    }
}

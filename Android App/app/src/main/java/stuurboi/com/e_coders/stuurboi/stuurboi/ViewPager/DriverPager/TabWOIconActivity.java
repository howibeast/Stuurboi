package stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverPager;

import android.content.Intent;
import android.os.Bundle;
import android.support.design.widget.TabLayout;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.Toast;

import stuurboi.com.e_coders.stuurboi.stuurboi.CurrentLocation;
import stuurboi.com.e_coders.stuurboi.stuurboi.Fragment.TripsFragment;
import stuurboi.com.e_coders.stuurboi.stuurboi.Fragment.RequestsFragment;
import stuurboi.com.e_coders.stuurboi.stuurboi.Fragment.RatingsFragment;
import stuurboi.com.e_coders.stuurboi.stuurboi.R;
import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.ViewPagerAdapter;

public class TabWOIconActivity extends AppCompatActivity {

    //This is our tablayout
    private TabLayout tabLayout;

    //This is our viewPager
    private ViewPager viewPager;

    //Fragments

    RequestsFragment requestsFragment;
    TripsFragment tripsFragment;
    RatingsFragment ratingsFragment;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tab_icon_less);
        //Initializing viewPager
        viewPager = (ViewPager) findViewById(R.id.viewpager);
        viewPager.setOffscreenPageLimit(3);

        //Initializing the tablayout
        tabLayout = (TabLayout) findViewById(R.id.tablayout);
        tabLayout.setupWithViewPager(viewPager);

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

        setupViewPager(viewPager);


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
                Intent custom_tab=new Intent(this,CurrentLocation.class);
                startActivity(custom_tab);
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

}

package stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager;

import android.content.Intent;
import android.os.Handler;
import android.support.v4.view.ViewPager;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import com.viewpagerindicator.CirclePageIndicator;

import java.util.ArrayList;
import java.util.Timer;
import java.util.TimerTask;

import stuurboi.com.e_coders.stuurboi.stuurboi.Model.ImageModel;
import stuurboi.com.e_coders.stuurboi.stuurboi.Model.SlidingImage_Adapter;
import stuurboi.com.e_coders.stuurboi.stuurboi.R;
import stuurboi.com.e_coders.stuurboi.stuurboi.SignIn;
import stuurboi.com.e_coders.stuurboi.stuurboi.Signup;

public class Slideshow extends AppCompatActivity {

    private static ViewPager mPager;
    private static int currentPage = 0;
    private static int NUM_PAGES = 0;
    private ArrayList<ImageModel> imageModelArrayList;

    // Creating button;
    Button Register;

    private int[] myImageList = new int[]{R.drawable.harley2,
                                            R.drawable.benz2,
                                            R.drawable.vecto,
                                            R.drawable.webshots
                                            ,R.drawable.bikess,
                                             R.drawable.img1};


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_slideshow);

        Register = (Button) findViewById(R.id.btnOpenSignin);

        imageModelArrayList = new ArrayList<>();
        imageModelArrayList = populateList();

        init();


        //   Adding click listener to button.
        Register.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                // Opening the user profile activity using intent.
                Intent intent = new Intent(Slideshow.this, SignIn.class);
                startActivity(intent);
            }
            });

        //If user presses on not registered
        findViewById(R.id.txtWebRegister).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                //open register screen
                finish();
                startActivity(new Intent(getApplicationContext(), Signup.class));
            }
        });
    }

    private ArrayList<ImageModel> populateList(){

        ArrayList<ImageModel> list = new ArrayList<>();

        for(int i = 0; i < 6; i++){
            ImageModel imageModel = new ImageModel();
            imageModel.setImage_drawable(myImageList[i]);
            list.add(imageModel);
        }

        return list;
    }

    private void init() {

        mPager = (ViewPager) findViewById(R.id.pager);
        mPager.setAdapter(new SlidingImage_Adapter(Slideshow.this,imageModelArrayList));

        CirclePageIndicator indicator = (CirclePageIndicator)
                findViewById(R.id.indicator);

        indicator.setViewPager(mPager);

        final float density = getResources().getDisplayMetrics().density;

//Set circle indicator radius
        indicator.setRadius(5 * density);

        NUM_PAGES =imageModelArrayList.size();

        // Auto start of viewpager
        final Handler handler = new Handler();
        final Runnable Update = new Runnable() {
            public void run() {
                if (currentPage == NUM_PAGES) {
                    currentPage = 0;
                }
                mPager.setCurrentItem(currentPage++, true);
            }
        };
        Timer swipeTimer = new Timer();
        swipeTimer.schedule(new TimerTask() {
            @Override
            public void run() {
                handler.post(Update);
            }
        }, 3000, 3000);

        // Pager listener over indicator
        indicator.setOnPageChangeListener(new ViewPager.OnPageChangeListener() {

            @Override
            public void onPageSelected(int position) {
                currentPage = position;

            }

            @Override
            public void onPageScrolled(int pos, float arg1, int arg2) {

            }

            @Override
            public void onPageScrollStateChanged(int pos) {

            }
        });

    }
}

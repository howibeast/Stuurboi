package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.app.ProgressDialog;
import android.content.ActivityNotFoundException;
import android.content.Intent;
import android.location.Location;
import android.os.Bundle;
import android.speech.RecognizerIntent;
import android.support.annotation.NonNull;
import android.support.design.widget.FloatingActionButton;
import android.support.design.widget.NavigationView;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.mapbox.android.core.location.LocationEngine;
import com.mapbox.android.core.location.LocationEngineListener;
import com.mapbox.android.core.location.LocationEnginePriority;
import com.mapbox.android.core.location.LocationEngineProvider;
import com.mapbox.android.core.permissions.PermissionsListener;
import com.mapbox.android.core.permissions.PermissionsManager;
import com.mapbox.api.directions.v5.models.DirectionsResponse;
import com.mapbox.geojson.Point;
import com.mapbox.mapboxsdk.Mapbox;
import com.mapbox.mapboxsdk.annotations.Marker;
import com.mapbox.mapboxsdk.annotations.MarkerOptions;
import com.mapbox.mapboxsdk.camera.CameraPosition;
import com.mapbox.mapboxsdk.camera.CameraUpdateFactory;
import com.mapbox.mapboxsdk.geometry.LatLng;
import com.mapbox.mapboxsdk.maps.MapView;
import com.mapbox.mapboxsdk.maps.MapboxMap;
import com.mapbox.mapboxsdk.maps.OnMapReadyCallback;
import com.mapbox.mapboxsdk.plugins.locationlayer.LocationLayerPlugin;
import com.mapbox.mapboxsdk.plugins.locationlayer.modes.RenderMode;
import com.mapbox.services.android.navigation.ui.v5.NavigationLauncher;
import com.mapbox.services.android.navigation.ui.v5.NavigationLauncherOptions;
import com.mapbox.services.android.navigation.ui.v5.route.NavigationMapRoute;
import com.mapbox.services.android.navigation.v5.navigation.NavigationRoute;
import com.mapbox.services.android.ui.geocoder.GeocoderAutoCompleteView;
import com.mapbox.services.api.geocoding.v5.GeocodingCriteria;
import com.mapbox.services.api.geocoding.v5.models.CarmenFeature;
import com.mapbox.services.commons.models.Position;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import stuurboi.com.e_coders.stuurboi.stuurboi.R;

public class ClientTabActivity extends AppCompatActivity
        implements LocationEngineListener, PermissionsListener {

        private MapView mapView;

    public static String userId;
        // variables for adding location layer
        private MapboxMap map;
        private PermissionsManager permissionsManager;       /** For drawing a map**/
        private LocationLayerPlugin locationPlugin;
        private LocationEngine locationEngine;


        // variables for adding a marker
        private Location currentLocation;  // Current Locations
        private Marker destinationMarker; // The Marker              /** For Marker and Coordinates**/
        private Marker originMarker;
        private LatLng originCoord;       // Origin Coordinates
        private LatLng destinationCoord;  // Destination Coordinates


        // variables for calculating and drawing a route
        private Point originPosition;
        private Point destinationPosition;           /** For coordinates positions **/
        private com.mapbox.api.directions.v5.models.DirectionsRoute currentRoute;        /** For drawing route **/
        private static final String TAG = "DirectionsActivity";
        private NavigationMapRoute navigationMapRoute;  /** For Navigating **/


        private Button button;

        private static final int PLACE_AUTOCOMPLETE_REQUEST_CODE = 1;

        public GeocoderAutoCompleteView autocomplete1;
        public GeocoderAutoCompleteView autocomplete2;

        protected static final int RESULT_SPEECH = 1;


        private String pickupLocation;
        private String destinationLocation;


        ProgressDialog progressDialog;
        String HttpUrl = "http://stuurboi.000webhostapp.com/users/request";
        RequestQueue requestQueue;

        //get the spinner from the xml.
        Spinner dropdown;
        //create a list of items for the spinner.
        String[] items ;
        ArrayAdapter<String> adapter ;
        EditText voucher;


        @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_client_tab);

            //Mapbox.getInstance(this, getString(R.string.access_token));
            Mapbox.getInstance(this, "pk.eyJ1Ijoic3R1ZGVudG1hcCIsImEiOiJjamtrcmIwaWcxMXc4M3BwaHE2OW45bGJiIn0.b0FwX2mm9V7lQ2K-JEQVSA");

            autocomplete1 = findViewById(R.id.query1);
            autocomplete2 = findViewById(R.id.query2);

            requestQueue = Volley.newRequestQueue(ClientTabActivity.this);
            progressDialog = new ProgressDialog(ClientTabActivity.this);

            FloatingActionButton fab = (FloatingActionButton) findViewById(R.id.fab);
            fab.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    Intent intent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
                    intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL, "en-US");

                    try {
                        startActivityForResult(intent, RESULT_SPEECH);
                    } catch (ActivityNotFoundException e) {
                        Toast.makeText(getApplicationContext(),
                                "This device doesn't support Speech to Text",
                                Toast.LENGTH_SHORT).show();
                    };
                }
            });

    /******************
     * MAP
     ****************/
    mapView = findViewById(R.id.mapView);
        mapView.onCreate(savedInstanceState);

        mapView.getMapAsync(new OnMapReadyCallback() {
        @Override
        public void onMapReady(final MapboxMap mapboxMap) {

            map = mapboxMap;
            enableLocationPlugin(); // Adding Current Locations
            mapboxMap.getUiSettings().setZoomControlsEnabled(true);
            mapboxMap.getUiSettings().setZoomGesturesEnabled(true);     /** Zoom Settings **/
            mapboxMap.getUiSettings().setScrollGesturesEnabled(true);
            mapboxMap.getUiSettings().setAllGesturesEnabled(true);


            condition();


            /** Navigate the directions **/
            button = findViewById(R.id.startButton);
            button.setOnClickListener(new View.OnClickListener() {
                public void onClick(View v) {
                    send();
                    getNavigation();
                }
            });
        }
    });
 }


    /**************************************************
     * CONDITION STATEMENTS
     ************************************************/

    public void condition(){

        /*** Adding a Marker and show red route***/
        originCoord = new LatLng(currentLocation.getLatitude(), currentLocation.getLongitude());
        query1();
        query2();
        map.addOnMapClickListener(new MapboxMap.OnMapClickListener() {
            @Override
            public void onMapClick(@NonNull LatLng point) {

                /*** Adding a Marker***/
                if (destinationMarker != null || originMarker !=null) {
                    map.removeMarker(originMarker);
                    map.removeMarker(destinationMarker);

                }
                destinationCoord = point;    // when you click a map it gives coordinates
                destinationMarker = map.addMarker(new MarkerOptions().position(destinationCoord));
                originMarker=map.addMarker(new MarkerOptions().position(originCoord));


                /*** Drawing red route ***/
                originPosition = Point.fromLngLat(originCoord.getLongitude(), originCoord.getLatitude());
                destinationPosition = Point.fromLngLat(destinationCoord.getLongitude(), destinationCoord.getLatitude());

                getRoute(originPosition, destinationPosition);

                button.setEnabled(true);
                button.setBackgroundResource(R.color.mapboxBlue);
            }
        });

    }
    /**************************
     * Route from Textboxes
     *************************/
    public void query1(){
        // Set up autocomplete widget

        autocomplete1.setAccessToken(Mapbox.getAccessToken());
        autocomplete1.setType(GeocodingCriteria.TYPE_POI);
        autocomplete1.setOnFeatureListener(new GeocoderAutoCompleteView.OnFeatureListener() {
            @Override
            public void onFeatureClick(CarmenFeature feature) {
                hideOnScreenKeyboard();
                Position position = feature.asPosition();
                updateMap1(position.getLatitude(), position.getLongitude());

                destinationCoord = new LatLng(currentLocation.getLatitude(), currentLocation.getLongitude());
                originPosition = Point.fromLngLat(position.getLongitude(), position.getLatitude());
                destinationPosition = Point.fromLngLat(destinationCoord.getLongitude(), destinationCoord.getLatitude());
                getRoute(originPosition, destinationPosition);
                button.setEnabled(true);
                button.setBackgroundResource(R.color.mapboxBlue);

            }
        });

    }
    public void  query2(){
        // Set up autocomplete widget

        autocomplete2.setAccessToken(Mapbox.getAccessToken());
        autocomplete2.setType(GeocodingCriteria.TYPE_POI);
        autocomplete2.setOnFeatureListener(new GeocoderAutoCompleteView.OnFeatureListener() {
            @Override
            public void onFeatureClick(CarmenFeature feature) {
                hideOnScreenKeyboard();
                Position position = feature.asPosition();
                updateMap2(position.getLatitude(), position.getLongitude());


                originCoord = new LatLng(currentLocation.getLatitude(), currentLocation.getLongitude());
                destinationPosition = Point.fromLngLat(position.getLongitude(), position.getLatitude());
                originPosition = Point.fromLngLat(originCoord.getLongitude(), originCoord.getLatitude());
                getRoute(originPosition, destinationPosition);
                button.setEnabled(true);
                button.setBackgroundResource(R.color.mapboxBlue);

            }
        });

    }

    private void hideOnScreenKeyboard() {
        try {
            InputMethodManager imm = (InputMethodManager) getSystemService(INPUT_METHOD_SERVICE);
            if (getCurrentFocus() != null) {
                imm.hideSoftInputFromWindow(this.getCurrentFocus().getWindowToken(), InputMethodManager.HIDE_NOT_ALWAYS);
            }
        } catch (Exception exception) {
            throw new RuntimeException(exception);
        }

    }

    private void updateMap1(double latitude, double longitude) {
        // Build marker
        map.addMarker(new MarkerOptions()
                .position(new LatLng(latitude, longitude))
                .title("Geocoder result"));

        // Animate camera to geocoder result location
        CameraPosition cameraPosition = new CameraPosition.Builder()
                .target(new LatLng(latitude, longitude))
                .zoom(15)
                .build();
        map.animateCamera(CameraUpdateFactory.newCameraPosition(cameraPosition), 5000, null);


    }
    private void updateMap2(double latitude, double longitude) {
        // Build marker
        map.addMarker(new MarkerOptions()
                .position(new LatLng(latitude, longitude))
                .title("Geocoder result"));

        // Animate camera to geocoder result location
        CameraPosition cameraPosition = new CameraPosition.Builder()
                .target(new LatLng(latitude, longitude))
                .zoom(15)
                .build();
        map.animateCamera(CameraUpdateFactory.newCameraPosition(cameraPosition), 5000, null);

    }

    /*****************************************************************************************************
     * Current Locations   -Initialize the location engine,
     *                     And add the user’s location to the map as a location layer
     ****************************************************************************************************/
    @SuppressWarnings( {"MissingPermission"})
    private void initializeLocationEngine() {
        LocationEngineProvider locationEngineProvider = new LocationEngineProvider(this);
        locationEngine = locationEngineProvider.obtainBestLocationEngineAvailable();
        locationEngine.setPriority(LocationEnginePriority.HIGH_ACCURACY);
        locationEngine.activate();

        Location lastLocation = locationEngine.getLastLocation();
        if (lastLocation != null) {
            currentLocation = lastLocation;
            setCameraPosition(lastLocation);
        } else {
            locationEngine.addLocationEngineListener(this);
        }
    }

    @SuppressWarnings( {"MissingPermission"})
    private void enableLocationPlugin() {
        // Check if permissions are enabled and if not request
        if (PermissionsManager.areLocationPermissionsGranted(this)) {
            // Create an instance of LOST location engine
            initializeLocationEngine();

            locationPlugin = new LocationLayerPlugin(mapView, map, locationEngine);
            locationPlugin.setRenderMode(RenderMode.COMPASS);
        } else {
            permissionsManager = new PermissionsManager(this);
            permissionsManager.requestLocationPermissions(this);
        }
    }

    private void setCameraPosition(Location location) {
        map.animateCamera(CameraUpdateFactory.newLatLngZoom(
                new LatLng(location.getLatitude(), location.getLongitude()), 13));
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        permissionsManager.onRequestPermissionsResult(requestCode, permissions, grantResults);
    }

    @Override
    public void onExplanationNeeded(List<String> permissionsToExplain) {

    }

    @Override
    public void onPermissionResult(boolean granted) {
        if (granted) {
            enableLocationPlugin();
        } else {
            finish();
        }
    }

    @Override
    @SuppressWarnings( {"MissingPermission"})
    public void onConnected() {
        locationEngine.requestLocationUpdates();
    }

    @Override
    public void onLocationChanged(Location location) {
        if (location != null) {
            currentLocation = location;
            setCameraPosition(location);
            locationEngine.removeLocationEngineListener(this);
        }
    }

    @Override
    @SuppressWarnings( {"MissingPermission"})
    protected void onStart() {
        super.onStart();
        if (locationEngine != null) {
            locationEngine.requestLocationUpdates();
        }
        if (locationPlugin != null) {
            locationPlugin.onStart();
        }
        mapView.onStart();
    }

    @Override
    protected void onStop() {
        super.onStop();
        if (locationEngine != null) {
            locationEngine.removeLocationUpdates();
        }
        if (locationPlugin != null) {
            locationPlugin.onStop();
        }
        mapView.onStop();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        mapView.onDestroy();
        if (locationEngine != null) {
            locationEngine.deactivate();
        }
    }

    /*************************************************************************
     *   Map Functions
     *************************************************************************/
    @Override
    public void onLowMemory() {
        super.onLowMemory();
        mapView.onLowMemory();
    }

    @Override
    protected void onResume() {
        super.onResume();
        mapView.onResume();
    }

    @Override
    protected void onPause() {
        super.onPause();
        mapView.onPause();
    }

    @Override
    protected void onSaveInstanceState(Bundle outState) {
        super.onSaveInstanceState(outState);
        mapView.onSaveInstanceState(outState);
    }

    /****************************************
     *    Drawing Route
     **************************************/

    private void getRoute(Point origin, Point destination) {
        NavigationRoute.builder(this)
                .accessToken(Mapbox.getAccessToken())
                .origin(origin)
                .destination(destination)
                .build()
                .getRoute(new Callback<DirectionsResponse>() {
                    @Override
                    public void onResponse(Call<DirectionsResponse> call, Response<DirectionsResponse> response) {
                        // You can get the generic HTTP info about the response
                        Log.d(TAG, "Response code: " + response.code());
                        if (response.body() == null) {
                            Log.e(TAG, "No routes found, make sure you set the right user and access token.");
                            return;
                        } else if (response.body().routes().size() < 1) {
                            Log.e(TAG, "No routes found");
                            return;
                        }

                        currentRoute = response.body().routes().get(0);

                        // Draw the route on the map
                        if (navigationMapRoute != null) {
                            navigationMapRoute.removeRoute();
                        } else {
                            navigationMapRoute = new NavigationMapRoute(null, mapView, map, R.style.NavigationMapRoute);
                        }
                        navigationMapRoute.addRoute(currentRoute);
                    }

                    @Override
                    public void onFailure(Call<DirectionsResponse> call, Throwable throwable) {
                        Log.e(TAG, "Error: " + throwable.getMessage());
                    }
                });
    }

    /*****************************************************************************
     * Navigating
     ****************************************************************************/
    public void getNavigation(){
        boolean simulateRoute = true;
        NavigationLauncherOptions options = NavigationLauncherOptions.builder()
                .directionsRoute(currentRoute)
                .shouldSimulateRoute(simulateRoute)
                .build();
        // Call this method with Context from within an Activity
        NavigationLauncher.startNavigation(ClientTabActivity.this, options);
    }

    /**********************************************************
     * Send to database
     *********************************************************/
    public void send(){
        pickupLocation=autocomplete1.getText().toString();
        destinationLocation=autocomplete2.getText().toString();


        // Showing progress dialog at user registration time.
        progressDialog.setMessage("Please Wait, We are Inserting Your Data on Server");
        progressDialog.show();


        // Creating string request with post method.
        StringRequest stringRequest = new StringRequest(Request.Method.POST, HttpUrl,
                new com.android.volley.Response.Listener<String>() {
                    @Override
                    public void onResponse(String ServerResponse) {

                        // Hiding the progress dialog after all task complete.
                        progressDialog.dismiss();

                        // Showing Echo Response Message Coming From Server.
                        Toast.makeText(ClientTabActivity.this, ServerResponse, Toast.LENGTH_LONG).show();
                    }
                },
                new com.android.volley.Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError volleyError) {

                        // Hiding the progress dialog after all task complete.
                        progressDialog.dismiss();

                        // Showing error message if something goes wrong.
                        Toast.makeText(ClientTabActivity.this, volleyError.toString(), Toast.LENGTH_LONG).show();
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
                // Adding All values to Params.

                // The firs argument should be same sa your MySQL database table columns.
                params.put("fromAddress", pickupLocation);
                params.put("toAddress", destinationLocation);
                //  params.put("receiverCell",voucher.getText().toString());
                // params.put("vehicleType",dropdown.toString());


                return params;
            }

        };
        // Creating RequestQueue.
        RequestQueue requestQueue = Volley.newRequestQueue(ClientTabActivity.this);

        // Adding the StringRequest object into requestQueue.
        requestQueue.add(stringRequest);


    }

}

/**CharSequence str1="Johannesburg, South Africa";
 CharSequence str2="Doornfontein, Johannesburg, South Africa";
 public void setText(){
 autocomplete1.setText(str1);
 autocomplete2.setText(str2);
 }**/


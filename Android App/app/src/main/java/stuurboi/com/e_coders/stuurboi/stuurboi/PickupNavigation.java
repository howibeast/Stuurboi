package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.content.Intent;
import android.location.Location;
import android.location.LocationManager;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.View;
import android.widget.Button;

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


import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.DriverHelper.Requests;


public class PickupNavigation extends AppCompatActivity implements LocationEngineListener, PermissionsListener {

    public static String from;
    public static String to;
    public static String userId;
    public static String price;
    public static String driverId;
    public static String fromAddress;
    public static String toAddress;

    public LatLng originCoord;       // Origin Coordinates
    public static LatLng destinationCoord;
    public static LatLng tempCoord;

    private MapView mapView;

    // variables for adding location layer
    private MapboxMap map;
    private PermissionsManager permissionsManager;
    /**
     * For drawing a map
     **/
    private LocationLayerPlugin locationPlugin;
    private LocationEngine locationEngine;


    // variables for adding a marker
    private Location currentLocation;  // Current Locations
    private Marker destinationMarker; // The Marker              /** For Marker and Coordinates**/
    private Marker originMarker;

    // variables for calculating and drawing a route
    private Point originPosition;
    private Point destinationPosition;
    /**
     * For coordinates positions
     **/
    private com.mapbox.api.directions.v5.models.DirectionsRoute currentRoute;
    /**
     * For drawing route
     **/
    private static final String TAG = "DirectionsActivity";
    private NavigationMapRoute navigationMapRoute;
    /**
     * For Navigating
     **/

    private Button button;
    final static ExecutorService tpe = Executors.newSingleThreadExecutor();
    private static Requests requests;

    private Double distance;
    private Double time;



    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_pickup_navigation);

        //Mapbox.getInstance(this, getString(R.string.access_token));
        Mapbox.getInstance(this, "pk.eyJ1Ijoic3R1ZGVudG1hcCIsImEiOiJjamtrcmIwaWcxMXc4M3BwaHE2OW45bGJiIn0.b0FwX2mm9V7lQ2K-JEQVSA");

        //requests=new Requests();
        /******************
         * MAP
         ****************/
        mapView = findViewById(R.id.mapView3);
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

                /** Navigate the directions **/
                button = findViewById(R.id.pickUpButton);

                setRoute();

                button.setOnClickListener(new View.OnClickListener() {
                    public void onClick(View v) {


                        tpe.submit(new Runnable() {
                            @Override
                            public void run() {
                                getNavigation();
                            }
                        });

                        Intent intent = new Intent(PickupNavigation.this, VerifyPickup.class);

                        Location temp = new Location(LocationManager.GPS_PROVIDER);
                        temp.setLatitude(getDestinationCoord().getLatitude());
                        temp.setLongitude(getDestinationCoord().getLongitude());

                        VerifyPickup.originCoord=getDestinationCoord();
                        VerifyPickup.destinationCoord=getTempCoord();
                        VerifyPickup.currentLocation=temp;

                        VerifyPickup.UserId=userId;  // for the requesting user
                        VerifyPickup.driverId=driverId;

                        VerifyPickup.fromAddress=getFromAddress();
                        VerifyPickup.toAddress=getToAddress();

                        VerifyPickup.price=price;
                        startActivity(intent);
                    }

                });
            }
        });


    }

    public void setRoute(){


        this.setOriginCoord(currentLocation);
        originPosition=Point.fromLngLat(this.getOriginCoord().getLongitude(), this.getOriginCoord().getLatitude());
        destinationPosition= Point.fromLngLat(this.getDestinationCoord().getLongitude(),this.getDestinationCoord().getLatitude());

        /** Adding a Marker**/
        if (destinationMarker != null) {
            map.removeMarker(destinationMarker);
        }

        destinationMarker = map.addMarker(new MarkerOptions().position(destinationCoord));

        getRoute(originPosition, destinationPosition);

        button.setEnabled(true);
        button.setBackgroundResource(R.color.mapboxBlue);
    }

    private void updateMap(double latitude, double longitude) {
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

    /***
     * GET AND SETTER
     */
    public  void setOriginCoord(Location currentLocation) {
        this.originCoord =  new LatLng(currentLocation.getLatitude(), currentLocation.getLongitude());;

    }
    public LatLng getOriginCoord() {
        return originCoord;
    }
    public LatLng getDestinationCoord() {
        return destinationCoord;
    }

    public LatLng getTempCoord() {
        return tempCoord;
    }

    /*****************************************************************************************************
     * Current Locations   -Initialize the location engine,
     *                     And add the user’s location to the map as a location layer
     ****************************************************************************************************/
    @SuppressWarnings({"MissingPermission"})
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

    @SuppressWarnings({"MissingPermission"})
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

        updateMap(currentLocation.getLatitude(),currentLocation.getLongitude());
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
    @SuppressWarnings({"MissingPermission"})
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
    @SuppressWarnings({"MissingPermission"})
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
    public void getNavigation() {

        boolean simulateRoute = true;
        NavigationLauncherOptions options = NavigationLauncherOptions.builder()
                .directionsRoute(currentRoute)
                .shouldSimulateRoute(simulateRoute)
                .build();
        // Call this method with Context from within an Activity
        NavigationLauncher.startNavigation(PickupNavigation.this, options);
    }

    /***********
     *   Next activity
     */
    public static String getFromAddress() {
        return fromAddress;
    }
    public static String getToAddress() {
        return toAddress;
    }

}

package stuurboi.com.e_coders.stuurboi.stuurboi;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.location.Location;
import android.support.annotation.NonNull;
import android.support.design.widget.NavigationView;
import android.support.v4.app.ActivityCompat;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;

import com.mapbox.android.core.location.LocationEngine;
import com.mapbox.android.core.location.LocationEngineListener;
import com.mapbox.android.core.location.LocationEnginePriority;
import com.mapbox.android.core.location.LocationEngineProvider;
import com.mapbox.android.core.permissions.PermissionsListener;
import com.mapbox.android.core.permissions.PermissionsManager;
import com.mapbox.mapboxsdk.Mapbox;
import com.mapbox.mapboxsdk.camera.CameraUpdateFactory;
import com.mapbox.mapboxsdk.geometry.LatLng;
import com.mapbox.mapboxsdk.maps.MapView;
import com.mapbox.mapboxsdk.maps.MapboxMap;
import com.mapbox.mapboxsdk.maps.OnMapReadyCallback;
import com.mapbox.mapboxsdk.plugins.locationlayer.LocationLayerPlugin;
import com.mapbox.mapboxsdk.plugins.locationlayer.modes.RenderMode;

import java.util.List;

import stuurboi.com.e_coders.stuurboi.stuurboi.R;
import stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity;

public class CurrentLocation extends AppCompatActivity implements  LocationEngineListener, PermissionsListener
{

    // Draw map
    private MapView mapView;
    // variables for adding location layer
    private MapboxMap map;

    private Location currentLocation;  // Current Locations

    private PermissionsManager permissionsManager;
    /**
     * For drawing a map
     **/
    private LocationLayerPlugin locationPlugin;
    private LocationEngine locationEngine;

    static String driverId;

    private Button button;

    public static String getUserId() {
        return driverId;
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_current_location);
        button = findViewById(R.id.checkButton);
        Mapbox.getInstance(this, "pk.eyJ1Ijoic3R1ZGVudG1hcCIsImEiOiJjamtrcmIwaWcxMXc4M3BwaHE2OW45bGJiIn0.b0FwX2mm9V7lQ2K-JEQVSA");

        /******************
         * MAP
         ****************/
        mapView = findViewById(R.id.mapView3);
        mapView.onCreate(savedInstanceState);

        mapView.getMapAsync(new OnMapReadyCallback() {
            @Override
            public void onMapReady(final MapboxMap mapboxMap) {

                map = mapboxMap;

                // Adding Current Locations
                setupMapView();
                mapboxMap.getUiSettings().setZoomControlsEnabled(true);
                mapboxMap.getUiSettings().setZoomGesturesEnabled(true);     /** Zoom Settings **/
                mapboxMap.getUiSettings().setScrollGesturesEnabled(true);
                mapboxMap.getUiSettings().setAllGesturesEnabled(true);


                button.setOnClickListener(new View.OnClickListener() {
                    public void onClick(View v) {
                        Intent intent = new Intent(CurrentLocation.this, DriverTabActivity.class);
                        startActivity(intent);
                    }
                });

            }
        });
    }


    private void setupMapView() {
        mapView.getMapAsync(new OnMapReadyCallback() {
            @Override
            public void onMapReady(final MapboxMap mapboxMap) {
                if (PermissionsManager.areLocationPermissionsGranted(CurrentLocation.this)) {
                    locationEngine = new LocationEngineProvider(CurrentLocation.this).obtainLocationEngineBy(LocationEngine.Type.ANDROID);
                    locationEngine.setPriority(LocationEnginePriority.HIGH_ACCURACY);
                    locationEngine.addLocationEngineListener(new LocationEngineListener() {
                        @Override
                        public void onConnected() {
                            if (ActivityCompat.checkSelfPermission(CurrentLocation.this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(CurrentLocation.this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
                                // TODO: Consider calling
                                //    ActivityCompat#requestPermissions
                                // here to request the missing permissions, and then overriding
                                //   public void onRequestPermissionsResult(int requestCode, String[] permissions,
                                //                                          int[] grantResults)
                                // to handle the case where the user grants the permission. See the documentation
                                // for ActivityCompat#requestPermissions for more details.
                                return;
                            }
                            locationEngine.requestLocationUpdates();
                        }

                        @Override
                        public void onLocationChanged(Location location) {
                            setCameraPosition(mapboxMap, location);
                            locationEngine.removeLocationEngineListener(this);
                        }
                    });
                    locationEngine.activate();
                    locationPlugin = new LocationLayerPlugin(mapView, mapboxMap, locationEngine);

                    button.setEnabled(true);
                    button.setBackgroundResource(R.color.mapboxBlue);
                }

                mapboxMap.getUiSettings().setCompassEnabled(false);
                mapboxMap.getUiSettings().setRotateGesturesEnabled(false);
                mapboxMap.getUiSettings().setTiltGesturesEnabled(false);
            }
        });
    }


    private void setCameraPosition(MapboxMap map, Location location) {
        map.animateCamera(CameraUpdateFactory.newLatLngZoom(
                new LatLng(location.getLatitude(), location.getLongitude()), 13));
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

                lastLocation.setLatitude(-26.195246);
                lastLocation.setLongitude(28.034088);

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


}

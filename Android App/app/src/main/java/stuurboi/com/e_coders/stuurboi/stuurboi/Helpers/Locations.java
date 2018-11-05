package stuurboi.com.e_coders.stuurboi.stuurboi.Helpers;

import com.mapbox.mapboxsdk.geometry.LatLng;

public class Locations {

    private LatLng originCoord;       // Origin Coordinates
    private LatLng destinationCoord;  // Destination Coordinates



  /** public Locations(LatLng originCoord, LatLng destinationCoord) {
        this.originCoord = originCoord;
        this.destinationCoord = destinationCoord;
    }**/

    public void setDestinationCoord(LatLng destinationCoord) {
        this.destinationCoord = destinationCoord;
    }

    public void setOriginCoord(LatLng originCoord) {
        this.originCoord = originCoord;
    }


    public LatLng getOriginCoord() {
        return originCoord;
    }

    public LatLng getDestinationCoord() {
        return destinationCoord;
    }

}

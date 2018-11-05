package stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.DriverHelper;

import com.google.gson.annotations.SerializedName;

public class Trips {


    @SerializedName("pickupLocation")
    private String fromAddress;
    @SerializedName("destinationLocation")
    private String toAddress;
    @SerializedName("mileage")
    private String mileage;
    @SerializedName("dateCreated")
    private String dateCreated;
    @SerializedName("duration")
    private String duration;
    @SerializedName("fare")
    private String fare;

    public String getFromAddress() {
        return fromAddress;
    }

    public void setFromAddress(String fromAddress) {
        this.fromAddress = fromAddress;
    }

    public String getToAddress() {
        return toAddress;
    }

    public void setToAddress(String toAddress) {
        this.toAddress = toAddress;
    }

    public String getMileage() {
        return mileage;
    }

    public void setMileage(String mileage) {
        this.mileage = mileage;
    }

    public String getDateCreated() {
        return dateCreated;
    }

    public void setDateCreated(String dateCreated) {
        this.dateCreated = dateCreated;
    }

    public String getDuration() {
        return duration;
    }

    public void setDuration(String duration) {
        this.duration = duration;
    }

    public String getFare() {
        return fare;
    }

    public void setFare(String fare) {
        this.fare = fare;
    }
}

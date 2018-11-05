package stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.DriverHelper;

import com.google.gson.annotations.SerializedName;

public class Requests {

    @SerializedName("id")
    private String userId;
    @SerializedName("fromAddress")
    private String fromAddress;
    @SerializedName("vehicleType")
    private String vehicleType;
    @SerializedName("receiverName")
    private String receiverName;
    @SerializedName("receiverCell")
    private String receiverCell;
    @SerializedName("toAddress")
    private String toAddress;
    @SerializedName("estimationPrice")
    private String price;

    public String getUserId ()
    {
        return userId;
    }

    public void setUserId (String userId)
    {
        this.userId = userId;
    }

    public String getFromAddress ()
    {
        return fromAddress;
    }

    public void setFromAddress (String fromAddress)
    {
        this.fromAddress = fromAddress;
    }

    public String getVehicleType ()
    {
        return vehicleType;
    }

    public void setVehicleType (String vehicleType)
    {
        this.vehicleType = vehicleType;
    }

    public String getReceiverName ()
    {
        return receiverName;
    }

    public void setReceiverName (String receiverName)
    {
        this.receiverName = receiverName;
    }

    public String getReceiverCell ()
    {
        return receiverCell;
    }

    public void setReceiverCell (String receiverCell)
    {
        this.receiverCell = receiverCell;
    }

    public String getToAddress ()
    {
        return toAddress;
    }

    public void setToAddress (String toAddress)
    {
        this.toAddress = toAddress;
    }

    @Override
    public String toString()
    {
        return "ClassRequest [userId = "+userId+", fromAddress = "+fromAddress+", vehicleType = "+vehicleType+", receiverName = "+receiverName+", receiverCell = "+receiverCell+", toAddress = "+toAddress+"]";
    }

    public String getPrice() {
        return price;
    }


    public Requests(String userId, String fromAddress, String toAddress, String receiverCell, String receiverName, String status,String price) {
        this.userId=userId;
        this.fromAddress=fromAddress;
        this.toAddress=toAddress;
        this.receiverCell=receiverCell;
        this.receiverName=receiverName;
        this.vehicleType=status;
        this.price=price;
    }
}

package stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.TripsHelper;

import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.TextView;

import stuurboi.com.e_coders.stuurboi.stuurboi.R;

public class RecyclerTripsHolders extends RecyclerView.ViewHolder implements View.OnClickListener{

    public TextView frmAddress;
    public TextView ToAddress;
    public TextView fare;
    public TextView mileages;
    public TextView dateCreated;

    public RecyclerTripsHolders(View itemView) {
        super(itemView);
        itemView.setOnClickListener(this);

        frmAddress = itemView.findViewById(R.id.tripDate);
        ToAddress = itemView.findViewById(R.id.tripDestination);
        fare =itemView.findViewById(R.id.tripPrice);
        mileages = itemView.findViewById(R.id.tripMile);
        dateCreated=itemView.findViewById(R.id.tripPickup);
    }

    /**
     * Called when a view has been clicked.
     *
     * @param v The view that was clicked.
     */
    @Override
    public void onClick(View v) {

    }
}

package stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.TripsHelper;

import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.support.annotation.NonNull;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import java.util.List;

import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.DriverHelper.Trips;
import stuurboi.com.e_coders.stuurboi.stuurboi.R;
import stuurboi.com.e_coders.stuurboi.stuurboi.VerifyRequest;
import stuurboi.com.e_coders.stuurboi.stuurboi.VerifyTrip;


public class RecyclerTripsAdapter extends RecyclerView.Adapter<RecyclerTripsHolders>{

    public TextView fromAddress;
    public TextView toAddress;
    public TextView fare;
    public TextView mileage;
    public TextView dateCreated;

    private List<Trips> itemList;
    private Context context;
    private Dialog mDialog;

    public RecyclerTripsAdapter(Context context, List<Trips> itemList) {
        this.itemList = itemList;
        this.context = context;

    }

    @Override
    public RecyclerTripsHolders onCreateViewHolder(ViewGroup parent, int viewType) {

        View layoutView = LayoutInflater.from(parent.getContext()).inflate(R.layout.trips, null);
        RecyclerTripsHolders rcv = new RecyclerTripsHolders(layoutView);

        layoutView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {


                Intent intent=new Intent(context,VerifyTrip.class);
                intent.putExtra("From",rcv.getPosition());
                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);


                String from = rcv.frmAddress.getText().toString();
                VerifyTrip.from=from;

                String to = rcv.ToAddress.getText().toString();
                VerifyTrip.to=to;

                String price=rcv.mileages.getText().toString();
                VerifyTrip.mileage=price;

                String fare=rcv.fare.getText().toString();
                VerifyTrip.fare=fare;

                context.startActivity(intent);
            }
        });
        return rcv;
    }

    @Override
    public void onBindViewHolder(@NonNull RecyclerTripsHolders holder, int position) {
        holder.frmAddress.setText(itemList.get(position).getFromAddress());
        holder.ToAddress.setText(itemList.get(position).getToAddress());
        holder.fare.setText("R " + itemList.get(position).getFare());
        holder.mileages.setText(itemList.get(position).getMileage()+" miles");
        holder.dateCreated.setText(itemList.get(position).getDateCreated());
    }


    @Override
    public int getItemCount() {
        return this.itemList.size();
    }
    }

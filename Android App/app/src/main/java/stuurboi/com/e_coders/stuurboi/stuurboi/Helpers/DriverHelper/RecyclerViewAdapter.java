package stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.DriverHelper;

import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import java.util.List;

import stuurboi.com.e_coders.stuurboi.stuurboi.R;
import stuurboi.com.e_coders.stuurboi.stuurboi.VerifyRequest;


public class RecyclerViewAdapter extends RecyclerView.Adapter<RecyclerViewHolders> {

    private List<Requests> itemList;
    private Context context;

    public RecyclerViewAdapter(Context context, List<Requests> itemList) {
        this.itemList = itemList;
        this.context = context;

    }

    @Override
    public RecyclerViewHolders onCreateViewHolder(ViewGroup parent, int viewType) {

        View layoutView = LayoutInflater.from(parent.getContext()).inflate(R.layout.list_item, null);
        RecyclerViewHolders rcv = new RecyclerViewHolders(layoutView);
        layoutView.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent=new Intent(context,VerifyRequest.class);
                intent.putExtra("From",rcv.getPosition());
                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);

                String userId=rcv.userId.getText().toString();
                VerifyRequest.userId=userId;

               String from = rcv.fromAddress.getText().toString();
                VerifyRequest.from=from;

                String to = rcv.toAddress.getText().toString();
                VerifyRequest.to=to;

                String price=rcv.price.getText().toString();
                VerifyRequest.price=price;

                String name=rcv.receiverName.getText().toString();
                VerifyRequest.receiverName=name;

                String cell=rcv.receiverCell.getText().toString();
                VerifyRequest.receiverCell=cell;

                context.startActivity(intent);
            }
        });
        return rcv;
    }

    @Override
    public void onBindViewHolder(RecyclerViewHolders holder, int position) {

        holder.fromAddress.setText(itemList.get(position).getFromAddress());
        holder.toAddress.setText(itemList.get(position).getToAddress());
        holder.price.setText("Price : R" + itemList.get(position).getPrice());

        holder.userId.setText(itemList.get(position).getUserId());
        holder.receiverName.setText(itemList.get(position).getReceiverName());
        holder.receiverCell.setText(itemList.get(position).getReceiverCell());
    }

    @Override
    public int getItemCount() {
        return this.itemList.size();
    }

}

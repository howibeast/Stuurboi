package stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.DriverHelper;

import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.TextView;

import stuurboi.com.e_coders.stuurboi.stuurboi.R;


public class RecyclerViewHolders extends RecyclerView.ViewHolder implements View.OnClickListener{

    public TextView fromAddress;
    public TextView toAddress;
    public TextView price;
    public TextView userId;
    public TextView receiverName;
    public TextView receiverCell;

    public RecyclerViewHolders(View itemView){
        super(itemView);
        itemView.setOnClickListener(this);

        fromAddress = itemView.findViewById(R.id.from_title);
        toAddress = itemView.findViewById(R.id.to_title);
        price = itemView.findViewById(R.id.request_price);

        userId=itemView.findViewById(R.id.text11);
        receiverCell=itemView.findViewById(R.id.text12);
        receiverName=itemView.findViewById(R.id.text13);
    }



    @Override
    public void onClick(View view) {

    }


}

package stuurboi.com.e_coders.stuurboi.stuurboi.Fragment;


import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.SimpleDividerItemDecoration;
import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.DriverHelper.Trips;
import stuurboi.com.e_coders.stuurboi.stuurboi.R;
import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.TripsHelper.RecyclerTripsAdapter ;

import static stuurboi.com.e_coders.stuurboi.stuurboi.ViewPager.DriverTabActivity.driverId;


/**
 * A simple {@link Fragment} subclass.
 */
public class TripsFragment extends Fragment {

    //public static String driverId;
    private RecyclerView recyclerView;
    private LinearLayoutManager layoutManager;
    private RecyclerTripsAdapter adapter;
    private LinearLayout emptyView;
    private RelativeLayout trips;

    public TripsFragment() {

    } // Required empty public constructor

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setHasOptionsMenu(true);
        getTrips();
    }


    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {


        View rootView =inflater.inflate(R.layout.fragment_trips, container, false);

        // 1. get a reference to recyclerView
        recyclerView = (RecyclerView) rootView.findViewById(R.id.recycler_view2);
        emptyView = (LinearLayout) rootView.findViewById(R.id.todo_list_empty_view2);
        trips=rootView.findViewById(R.id.list_trips_view);

        recyclerView.addItemDecoration(new SimpleDividerItemDecoration(getActivity().getApplicationContext()));

        // 2. set layoutManger
        recyclerView.setLayoutManager(new LinearLayoutManager(getActivity()));

        // Inflate the layout for this fragment
        return rootView ;
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        inflater.inflate(R.menu.menu_trips_fragment, menu);
        super.onCreateOptionsMenu(menu, inflater);
    }

    private final String TAG = "viewTrips";

    public void getTrips() {

        RequestQueue queue = Volley.newRequestQueue(getActivity().getApplicationContext());
        String url ="http://stuurboi.000webhostapp.com/users/getTrips";
            // Creating string request with post method.
            StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                    new com.android.volley.Response.Listener<String>() {
                        @Override
                        public void onResponse(String response) {
                            Log.d(TAG, "Response " + response);
                            GsonBuilder builder = new GsonBuilder();
                            Gson mGson = builder.create();
                            List<Trips> posts = new ArrayList<Trips>();


                            posts = Arrays.asList(mGson.fromJson(response, Trips[].class));

                            adapter = new RecyclerTripsAdapter(getActivity().getApplicationContext(), posts);

                            if (adapter.getItemCount() == 0)
                            {
                                trips.setVisibility(View.GONE);
                                emptyView.setVisibility(View.VISIBLE);
                            }
                            else {
                                trips.setVisibility(View.VISIBLE);
                                emptyView.setVisibility(View.GONE);
                                recyclerView.setAdapter(adapter);
                            }
                        }
                    },
                    new com.android.volley.Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError volleyError) {
                            // Showing error message if something goes wrong.
                            Log.d(TAG, "Error " + volleyError.getMessage());
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

                    params.put("driverId", driverId);
                    return params;
                }

            };
            // Creating RequestQueue.
             //RequestQueue requestQueue = Volley.newRequestQueue(getActivity().getApplicationContext());
             queue.add(stringRequest);
        }

}

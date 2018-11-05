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

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import java.net.URISyntaxException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.DriverHelper.RecyclerViewAdapter;
import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.DriverHelper.Requests;
import stuurboi.com.e_coders.stuurboi.stuurboi.Helpers.SimpleDividerItemDecoration;
import stuurboi.com.e_coders.stuurboi.stuurboi.R;

public  class RequestsFragment extends Fragment {

    public static String driverId;
    private final String TAG = "viewRequests";
    private RecyclerView recyclerView;
    private LinearLayoutManager layoutManager;
    private RecyclerViewAdapter adapter;
    private LinearLayout emptyView;

    public RequestsFragment() {

    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setHasOptionsMenu(true);

        requestJsonObject();
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        View rootView =inflater.inflate(R.layout.fragment_requests, container, false);
        // 1. get a reference to recyclerView
        recyclerView = rootView.findViewById(R.id.recycler_view);
        emptyView = (LinearLayout) rootView.findViewById(R.id.todo_list_empty_view);
        recyclerView.addItemDecoration(new SimpleDividerItemDecoration(getActivity().getApplicationContext()));
        // 2. set layoutManger
        recyclerView.setLayoutManager(new LinearLayoutManager(getActivity()));
        // Inflate the layout for this fragment

        return rootView ;
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
            inflater.inflate(R.menu.menu_requests_fragment, menu);
            super.onCreateOptionsMenu(menu, inflater);
    }

    private  void requestJsonObject(){

        RequestQueue queue = Volley.newRequestQueue(getActivity().getApplicationContext());
        String url ="http://stuurboi.000webhostapp.com/users/getRequests";

        StringRequest stringRequest = new StringRequest(Request.Method.GET, url, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.d(TAG, "Response " + response);
                GsonBuilder builder = new GsonBuilder();
                Gson mGson = builder.create();
                List<Requests> posts = new ArrayList<Requests>();


                posts = Arrays.asList(mGson.fromJson(response, Requests[].class));

                adapter = new RecyclerViewAdapter(getActivity().getApplicationContext(), posts);

                if (adapter.getItemCount() == 0)
                {
                    recyclerView.setVisibility(View.GONE);
                    emptyView.setVisibility(View.VISIBLE);
                }
                else {
                    recyclerView.setVisibility(View.VISIBLE);
                    emptyView.setVisibility(View.GONE);
                    recyclerView.setAdapter(adapter);
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Log.d(TAG, "Error " + error.getMessage());
            }
        });
        queue.add(stringRequest);
    }

}

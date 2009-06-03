package com.ywb;

import java.util.HashMap;
import java.util.Iterator;

import cascading.flow.FlowProcess;
import cascading.operation.Aggregator;
import cascading.operation.AggregatorCall;
import cascading.operation.BaseOperation;
import cascading.tuple.Fields;
import cascading.tuple.Tuple;

/**
 * Class CountPvUv is an {@link Aggregator} that returns the given number of
 * {@link Tuple}.
 * <p/>
 * By default, it returns the first Tuple of {@link Fields#ARGS} found.
 */
public class CountPvUv extends BaseOperation<HashMap<String, Integer>>
		implements Aggregator<HashMap<String, Integer>> {
	private static final long serialVersionUID = -7024696033678404565L;
	public static final String FIELD_PV = "pv";
	public static final String FIELD_UV = "uv";

	/**
	 * Selects and returns the limit number of Tuples.
	 */
	public CountPvUv() {
		super(new Fields(FIELD_UV, FIELD_PV));
	}

	/**
	 * Selects and returns the limit number of Tuples.
	 * 
	 * @param fieldDeclaration
	 *            of type Fields
	 */
	public CountPvUv(Fields fieldDeclaration) {
		super(fieldDeclaration);
	}

	public void start(FlowProcess flowProcess,
			AggregatorCall<HashMap<String, Integer>> aggregatorCall) {
		if (aggregatorCall.getContext() == null)
			aggregatorCall.setContext(new HashMap<String, Integer>());
		else
			aggregatorCall.getContext().clear();
	}

	public void aggregate(FlowProcess flowProcess,
			AggregatorCall<HashMap<String, Integer>> aggregatorCall) {
		HashMap<String, Integer> map = aggregatorCall.getContext();
		String key = aggregatorCall.getArguments().getString(0);
		map.put(key, (map.containsKey(key) ? map.get(key) : 0) + 1);
	}

	public void complete(FlowProcess flowProcess,
			AggregatorCall<HashMap<String, Integer>> aggregatorCall) {
		HashMap<String, Integer> map = aggregatorCall.getContext();
		Tuple tuple = new Tuple();
		int pv = 0;
		tuple.add(map.size());
		Iterator<Integer> it = map.values().iterator();
		while (it.hasNext()) {
			pv += it.next();
		}
		tuple.add(pv);
		aggregatorCall.getOutputCollector().add(tuple);
	}
}

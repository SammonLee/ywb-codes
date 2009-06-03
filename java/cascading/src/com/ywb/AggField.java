package com.ywb;

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
public class AggField extends BaseOperation<ComparableHashMap<String, Integer>>
		implements Aggregator<ComparableHashMap<String, Integer>> {
	private static final long serialVersionUID = -7024696033678404565L;
	public static final String FIELD_MAP = "map";

	/**
	 * Selects and returns the limit number of Tuples.
	 */
	public AggField() {
		super(new Fields(FIELD_MAP));
	}

	/**
	 * Selects and returns the limit number of Tuples.
	 * 
	 * @param fieldDeclaration
	 *            of type Fields
	 */
	public AggField(Fields fieldDeclaration) {
		super(fieldDeclaration);
	}

	public void start(FlowProcess flowProcess,
			AggregatorCall<ComparableHashMap<String, Integer>> aggregatorCall) {
		if (aggregatorCall.getContext() == null)
			aggregatorCall.setContext(new ComparableHashMap<String, Integer>());
		else
			aggregatorCall.getContext().clear();
	}

	public void aggregate(FlowProcess flowProcess,
			AggregatorCall<ComparableHashMap<String, Integer>> aggregatorCall) {
		ComparableHashMap<String, Integer> map = aggregatorCall.getContext();
		String key = aggregatorCall.getArguments().getString(0);
		map.put(key, (map.containsKey(key) ? map.get(key) : 0) + 1);
	}

	public void complete(FlowProcess flowProcess,
			AggregatorCall<ComparableHashMap<String, Integer>> aggregatorCall) {
		ComparableHashMap<String, Integer> map = aggregatorCall.getContext();
		Tuple tuple = new Tuple();
		tuple.add(map);
		aggregatorCall.getOutputCollector().add(tuple);
	}
}

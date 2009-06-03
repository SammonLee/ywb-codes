package com.ywb;

import java.util.Iterator;
import java.util.Map;

import cascading.flow.FlowProcess;
import cascading.operation.BaseOperation;
import cascading.operation.Function;
import cascading.operation.FunctionCall;
import cascading.tuple.Fields;
import cascading.tuple.Tuple;
import cascading.tuple.TupleEntry;
import cascading.tuple.TupleEntryCollector;

@SuppressWarnings("unchecked")
public class FlatMap extends BaseOperation implements Function {

	/**
	 * 
	 */
	private static final long serialVersionUID = 4934924991866976034L;
	private int mapField=0;

	public FlatMap(Fields fields) {
		super(fields);
	}
	public FlatMap(Fields fields, int field) {
		super(fields);
		mapField = field;
	}
	@Override
	public void operate(FlowProcess flow, FunctionCall func) {
		TupleEntry args = func.getArguments();
		TupleEntryCollector collector = func.getOutputCollector();
		Map map = (Map) args.get(this.mapField);
		Iterator<Integer> it = map.keySet().iterator();
		while (it.hasNext()) {
			Tuple output = new Tuple();
			Comparable k = it.next();
			output.add(k);
			output.add((Comparable) map.get(k));
			collector.add(output);	
		}
	}

}

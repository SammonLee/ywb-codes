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

@SuppressWarnings("unchecked")
public class PvUv extends BaseOperation implements Function {

	/**
	 * 
	 */
	private static final long serialVersionUID = 330028846363026406L;
	private int mapField=0;

	public PvUv(Fields fields) {
		super(fields);
	}
	public PvUv(Fields fields, int field) {
		super(fields);
		mapField = field;
	}

	@Override
	public void operate(FlowProcess flow, FunctionCall func) {
		TupleEntry args = func.getArguments();
		Map map = (Map) args.get(this.mapField);
//		 System.out.println(map);
		int pv = 0;
		Tuple output = new Tuple();
		output.add(map.size());
		Iterator<Integer> it = map.values().iterator();
		while (it.hasNext()) {
			pv += it.next();
		}
		output.add(pv);
		func.getOutputCollector().add(output);
	}

}

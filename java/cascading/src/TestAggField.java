import java.util.Map;
import java.util.Properties;

import cascading.cascade.Cascades;
import cascading.flow.Flow;
import cascading.flow.FlowConnector;
import cascading.operation.Aggregator;
import cascading.operation.Identity;
import cascading.operation.aggregator.Count;
import cascading.operation.regex.RegexSplitter;
import cascading.pipe.Each;
import cascading.pipe.Every;
import cascading.pipe.GroupBy;
import cascading.pipe.Pipe;
import cascading.pipe.SubAssembly;
import cascading.scheme.Scheme;
import cascading.scheme.TextLine;
import cascading.tap.Lfs;
import cascading.tap.SinkMode;
import cascading.tap.Tap;
import cascading.tuple.Fields;

import com.ywb.AggField;
import com.ywb.PvUv;

public class TestAggField {
	public static class MyAssembly extends SubAssembly {
		public MyAssembly() {
			// the 'head' of the pipe assembly
			Pipe assembly = new Pipe("wordcount");

			RegexSplitter function = new RegexSplitter(new Fields("id",
					"lower", "upper"));
			Pipe table = new Each(assembly, new Fields("line"), function);
			assembly = new GroupBy(table, new Fields("id"));
			Aggregator count = new AggField(new Fields("map"));
			Pipe aggPipe = new Every(assembly, new Fields("lower"), count,
					new Fields("id", "map"));
			Pipe mapPipe = new Each(aggPipe, new Fields("id", "map"),
					new Identity());
			Pipe pvuvPipe = new Each(mapPipe, new Fields("map"), new PvUv(
					new Fields("uv", "pv")), new Fields("id", "pv", "uv"));
			pvuvPipe = new Pipe("pvuv", pvuvPipe);
//			Pipe flatPipe = new Each(assembly, new Fields("map"), new FlatMap(
//					new Fields("letter", "count")), new Fields("id", "letter",
//					"count"));
////			flatPipe = new Pipe("flat", flatPipe);
//			Aggregator count2 = new AggField(new Fields("map"));
//			Pipe aggPipe = new Every(assembly, new Fields("lower"), count2,
//					new Fields("id", "map"));
			Pipe flatPipe = new GroupBy("flat", table, new Fields("id"));
			flatPipe = new Every(flatPipe, new Count());
			setTails(pvuvPipe, flatPipe);
//			setTails(pvuvPipe);
		}
	}

	@SuppressWarnings("unchecked")
	public static void main(String[] args) {
		String inputPath = args[0];
		String outputPath = args[1];
		// define source and sink Taps.
		Scheme sourceScheme = new TextLine(new Fields("line"));
		Tap source = new Lfs(sourceScheme, inputPath);

		Scheme sinkScheme = new TextLine();
		Tap sink1 = new Lfs(sinkScheme, outputPath + "/a", SinkMode.REPLACE);
		Tap sink2 = new Lfs(sinkScheme, outputPath + "/b", SinkMode.REPLACE);

		SubAssembly pipe = new MyAssembly();

		// initialize app properties, tell Hadoop which jar file to use
		Properties properties = new Properties();
		FlowConnector.setApplicationJarClass(properties, TestAggField.class);

		// plan a new Flow from the assembly using the source and sink Taps
		Map<String, Tap> sinks = Cascades.tapsMap(pipe.getTails(), Tap.taps(
				sink1, sink2));
		FlowConnector flowConnector = new FlowConnector();
		Flow flow = flowConnector.connect("word-count", source, sinks, pipe);

		// execute the flow, block until complete
		flow.writeDOT("test.dot");
		flow.complete();
	}
}

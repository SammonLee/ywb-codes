import java.util.Map;
import java.util.Properties;

import cascading.cascade.Cascades;
import cascading.flow.Flow;
import cascading.flow.FlowConnector;
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
import com.ywb.FlatMap;

public class TestAggField {
	public static class MyAssembly extends SubAssembly {
		private static final long serialVersionUID = 1L;

		public MyAssembly() {
			// the 'head' of the pipe assembly
			Pipe assembly = new Pipe("wordcount");

			RegexSplitter function = new RegexSplitter(new Fields("id",
					"lower", "upper"));
			Pipe table = new Each(assembly, new Fields("line"), function);
			assembly = new GroupBy(table, new Fields("id"));
			assembly = new Every(assembly, new Fields("lower"), new AggField());
			Pipe copy= new Pipe("copy", assembly);

			Pipe pv1 = new Each(copy, new Fields("map"), new FlatMap(new Fields("pv", "uv")));

			Pipe pv2 = new Pipe("out", assembly);

			setTails(pv1, pv2);
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

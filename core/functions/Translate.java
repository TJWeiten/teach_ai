import java.io.*;

public class Translate {

	// This method will take the user's MD5 hashed username and open their preconverted AI file
	// then translate it to Java code and 
	public static void main(String[] args) throws IOException {
	
		String usernameMD5 = args[0];
		String directoryPath = args[1];
		String filename = "ai" + usernameMD5;
		String currentLine;
		
		// PLEASE CHANGE THESE FILE PATHS!
		File fileIn  = new File(directoryPath + "/data/preconverted/" + filename + ".txt");
        File fileOut = new File(directoryPath + "/data/postconverted/" + filename + ".java");

        BufferedReader in = new BufferedReader(new FileReader(fileIn));
        BufferedWriter out = new BufferedWriter(new FileWriter(fileOut));
		
		String firstLine = "package postconverted;\n\npublic class ai" + usernameMD5 + " extends BotMaster {\n\n// Master bot code is here!\n\n";
		out.write(firstLine);
	
		while((currentLine = in.readLine()) != null) {
			currentLine = translate(currentLine);
			out.write(currentLine);
		}
		
		out.write("\n}");
		
		in.close();
		out.close();
	
	}
	
	// TODO : TRANSLATION CODE IN HERE
	private static String translate(String line) {
		return line + "\n";	
	}

}
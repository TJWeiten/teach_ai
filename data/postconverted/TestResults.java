package postconverted;

import java.util.Arrays;
import java.util.List;
import java.util.Random;

public class TestResults {
	
	public static void main(String[] args) {
		String data = args[0];
		int[][][][] results = new int[4][2][4][13];
		
		String[] rounds = data.split("a");
		for(int i = 0; i < 4; i++){
			String[] players = rounds[i].split("b");
			for(int j = 0; j < 2; j++){
				String[] types = players[j].split("c");
				for(int k = 0; k < 4; k++){
					String[] cards = types[k].split("d");
					for(int l = 0; l < 13; l++){
						int card = Integer.parseInt(cards[l]);
						results[i][j][k][l] = card;					
					}
				}
			}
		}
		
		int[][] points = new int[4][4];
		for(int i = 0; i < 4; i++){
			for(int j = 0; j < 4; j++){
				points[i][j] = 0;
			}
		}
		
		String output = "<ul>";
		String players[] = {"You","Player 2","Player 3","Player 4"};
		for(int i = 0; i < 4; i++){
			output += "</ul>Round " + (i + 1) + ":<br>";
			output += "You were dealt the following cards:<br>";
			for(int j = 0; j < 12; j++){
				output += name(results[i][0][0][j], false) + ", ";
			}
			output += name(results[i][0][0][12], false) + "<br><br><br><br>";
			
			output += passedCards(results[i]);
			
			int firstPlayer = 0;
			for(int j = 0; j < 4; j++) {
				if(Arrays.asList(results[i][1][j]).contains(0)) {
					firstPlayer = j;
				}
			}
			int suit = -1;
		
			for(int handNumber = 0; handNumber < 13; handNumber++){
				output += "Trick " + (handNumber + 1) + ":<br>";
				output += "Your hand contains the following cards:<br>";
				for(int j = handNumber; j < 12; j++){
					output += name(results[i][1][0][j], false) + ", ";
				}
				output += name(results[i][1][0][12], false) + "<br><br>";
				int cards[] = new int[4];
				for(int j = 0; j < 4; j++){
					cards[j] = results[i][1][(firstPlayer + j) % 4][handNumber];
					output += players[(firstPlayer + j) % 4] + " played " + name(cards[j], true) + "<br>";
				}
				
				suit = cards[0] / 13;
				int trick = 0;
				int currPoints = 0;
				for(int j = 0; j < 4; j++){
					if(results[i][1][j][handNumber] / 13 == 3){
						currPoints += 1;
					}
					else if(results[i][1][j][handNumber] == 36){
						currPoints += 13;
					}
					if(cards[j] > cards[trick] && cards[j] / 13 == suit){
						trick = j;
					}
				}
				output += players[(firstPlayer + trick) % 4] + " took the trick, containing " + currPoints + " points.<br><br><br><br>";
				points[i][(firstPlayer + trick) % 4] += currPoints;
				firstPlayer = trick;
				
			
			
			}
			for(int j = 0; j < 4; j++){
				if(points[i][j] == 26){
					output += players[j] + " shot the moon on this round.";
					for(int k = 0; k < 4; k++){
						if(k != j){
							points[i][k] = 26;
						}
						else{
							points[i][k] = 0;
						}
					}
				}
			}
			
		}
		for(int i = 0; i < 4; i++){
			points[0][i] = points[0][i] + points[1][i] + points[2][i] + points[3][i];
			output += players[i] + " had a total of " + points[0][i] + " points.<br>";

		}
		
		int winner = 0;
		for(int i = 1; i < 4; i++){
			if(points[0][i] < points[0][winner]){
				winner = i;
			}
		}
		output += players[winner] + " won the game.";

		output += "</ul>";
		System.out.println(output);
		
	}
	
	public static String passedCards(int[][][] data){
		int cards[] = new int[13];
		int card = 0;
		for(int i = 0; i < 13; i++){
			boolean passed = true;
			for(int j = 0; j < 13; j++){
				if(data[0][0][i] == data[1][0][j]){
					passed = false;
				}			
			}
			if(passed){
				cards[card++] = data[0][0][i];
			}
		}
		return "You passed the " + name(cards[0], false) + ", the " + name(cards[1], false) + ", and the " + name(cards[2], false) + ".<br><br><ul>";
	
	
	
	}
	
	public static String name(int card, boolean a){
		int suit = card / 13;
		int value = card - (suit * 13);
		
		String suitName;
		switch(suit){
			case 0: suitName = "Clubs"; break;
			case 1: suitName = "Diamonds"; break;
			case 2: suitName = "Spades"; break;
			default: suitName = "Hearts"; break;	
		}
		
		String valueName = (value + 2) + "";
		if(!a){
			switch (value){
				case 9: valueName = "Jack"; break;
				case 10: valueName = "Queen"; break;
				case 11: valueName = "King"; break;
				case 12: valueName = "Ace"; break;
			}
		}
		else{
			switch (value){
				case 9: valueName = "a Jack"; break;
				case 10: valueName = "a Queen"; break;
				case 11: valueName = "a King"; break;
				case 12: valueName = "an Ace"; break;
				default: valueName = "a " + valueName; break;
			}
		}
	
		return valueName + " of " + suitName;
	}
	
	
	
}
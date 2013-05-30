package postconverted;

import java.util.Arrays;
import java.util.List;
import java.util.Random;
import postconverted.*;

public class Game {
	
	public static void main(String[] args) {
		// String[] botNames = {"postconverted.ai5a4523e3f33e705587d0fc4e98eb09ba", "postconverted.ai5a4523e3f33e705587d0fc4e98eb09ba", "postconverted.ai5a4523e3f33e705587d0fc4e98eb09ba", "postconverted.ai5a4523e3f33e705587d0fc4e98eb09ba"};
		int[][][][] results = playGame(args);
		String result = "";
		for(int i = 0; i < 4; i++){
			for(int j = 0; j < 2; j++){
				for(int k = 0; k < 4; k++){
					for(int l = 0; l < 13; l++){
						result += results[i][j][k][l];
						if(l < 12){
							result += "d";
						}
						else if (k < 3){
							result += "c";
						}
						else if(j < 1){
							result += "b";
						}
						else if(i < 3){
							result += "a";
						}
					}
				}				
			}
		}
		System.out.println(result);
	}
	
	public static int[][][][] playGame(String[] botNames) {
		int[][][][] gameData = new int[4][2][4][13];
		for(int i = 0; i < 4; i++) {
			gameData[i] = playRound(i, botNames);
		}
		return gameData;
	}
	
	public static int[][][] playRound(int roundNumber, String[] botNames) {
		int[][] startingHand = new int[4][13];
		int[][] currentHand = new int[4][13];
		int[][] cardsPlayed = new int[4][13];
		
		int[] cards = randomDeck();
		for(int i = 0; i < 13; i++) {
			for(int j = 0; j < 4; j++) {
				startingHand[j][i] = cards[(i * 4) + j];
				cardsPlayed[j][i] = -1;
			}
		}
		for(int i = 0; i < 4; i++) {
			Arrays.sort(startingHand[i]);
		}
		BotMasterInterface[] bots = new BotMasterInterface[4];
		try{	
			for(int i = 0; i < 4; i++) {
				Class cls;
				cls = Class.forName(botNames[i]);
				bots[i] = (BotMasterInterface) cls.newInstance();
			}
		}
		catch(ClassNotFoundException e) {
			//System.out.println("ClassNotFoundException");
		}
		catch(InstantiationException e) {
			//System.out.println("InstantiationException");
			//e.printStackTrace();
		}
		catch(IllegalAccessException e) {
			//System.out.println("IllegalAccessException");
			//e.printStackTrace();
		}
		
		// Swap 3 cards
		int[][] swapCards = new int[4][3];
		if(roundNumber != 3) {
			
			for(int i = 0; i < 4; i++) {
				swapCards[i] = bots[i].passThreeCards(startingHand[i]);
				Arrays.sort(swapCards[i]);
			}
			for(int i = 0; i < 4; i++) {
				int card = 0;
				for(int j = 0; j < 13; j++) {
					if(card < 3 && startingHand[i][j] == swapCards[i][card]) {
						currentHand[i][j] = swapCards[(i + 3 - roundNumber) % 4][card++];
					} else {
						currentHand[i][j] = startingHand[i][j];
					}
				}
			}
		} else {
			for(int i = 0; i < 4; i++) {
				for(int j = 0; j < 13; j++) {
					currentHand[i][j] = startingHand[i][j];
				}
			}
		}
		
		int firstPlayer = 0;
		boolean heartsBroken = false;
		int suit = -1;
		for(int i = 0; i < 4; i++) {
			if(Arrays.asList(currentHand[i]).contains(0)) {
				firstPlayer = i;
			}
		}
		
		for(int handNumber = 0; handNumber < 13; handNumber++) {
			suit = -1;
			int[] card = new int[4];
			for(int i = 0; i < 4; i++){
				bots[(firstPlayer + i) % 4].updatePlayedCards(cardsPlayed, currentHand[(firstPlayer + i) % 4]);
				card[(firstPlayer + i) % 4] = -1;
				if(i == 0){
					card[firstPlayer] = bots[firstPlayer].playFirstCard(getFirstValidCards(heartsBroken, currentHand[firstPlayer]));
					suit = card[firstPlayer] / 13;
					
				}
				else{
					card[(firstPlayer + i) % 4] = bots[(firstPlayer + i) % 4].playCard(getValidCards(heartsBroken, suit, currentHand[(firstPlayer + i) % 4]));
					
				}
				if(card[(firstPlayer + i) % 4] > 38){
					heartsBroken = true;
				}
				for(int j = 0; j < 13; j++){
					if(currentHand[(firstPlayer + i) % 4][j] == card[(firstPlayer + i) % 4]){
						currentHand[(firstPlayer + i) % 4][j] = -1;
					}
				}
				cardsPlayed[(firstPlayer + i) % 4][handNumber] = card[(firstPlayer + i) % 4];
				
				
			}
			int max = 0;
			for(int i = 0; i < 4; i++){
				if(card[i] > max && card[i]/13 == suit){
					firstPlayer = i;
					max = card[i];
				}
			}
		}
	
		int[][][] roundValues = new int[2][4][13];
		roundValues[0] = startingHand;
		roundValues[1] = cardsPlayed;
		return roundValues;
		
	}
	
	public static int[] getFirstValidCards(boolean heartsBroken, int[] hand) {
		int[] answer = new int[0];
		for(int i = 0; i < 13; i++) {
			if(hand[i] > -1 && (hand[i] < 39 || heartsBroken)) {
				int[] temp = new int[answer.length + 1];
				for(int j = 0; j < answer.length; j++) {
					temp[j] = answer[j];
					 
				}
				temp[answer.length]= hand[i];
				answer = temp;
			}
		}
		if(answer.length == 0) {
			return getFirstValidCards(true, hand);
		} else {
			return answer;
		}
	}
	
	public static int[] getValidCards(boolean heartsBroken, int suit, int[] hand) {
		int[] answer = new int[0];
		for(int i = 0; i < 13; i++) {
			if(hand[i] > -1 && (hand[i]/13 == suit)) {
				int[] temp = new int[answer.length + 1];
				for(int j = 0; j < answer.length; j++) {
					temp[j] = answer[j];
					
				}
				temp[answer.length]= hand[i]; 
				answer = temp;
			}
		}
		if(answer.length == 0){
			return getFirstValidCards(heartsBroken, hand);
		}
		else{
			return answer;
		}
	}

	public static int[] randomDeck(){
		int cards[] = new int[52];
		for(int i = 0; i < 52; i++){
			cards[i] = i;
		}
		int j = 0;
		Random rand = new Random();
		for (int i = 51; i > 0; i--){
			j = rand.nextInt(i);
			int card = cards[i];
			cards[i] = cards[j];
			cards[j] = card;
		}
		return cards;
	}
	
}
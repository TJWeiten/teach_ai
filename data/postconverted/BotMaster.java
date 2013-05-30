package postconverted;

public abstract class BotMaster implements BotMasterInterface {
 
        int[] userHand = new int[13];
        int[][] playedCards = new int[13][4];

        public int playFirstCard(int[] validCards) {
			double highestScore = -Double.MAX_VALUE;
			int highest = 0;
			for( int i = 0; i < validCards.length; i++ ) {
				double score = rateFirstCard(validCards[i]);
				if( score > highestScore ) {
					highestScore = score;
					highest = i;
				}
			}
			return validCards[highest];
		}
       
        public int playCard(int[] validCards) {
			double highestScore = -Double.MAX_VALUE;
			int highest = 0;
			for( int i = 0; i < validCards.length; i++ ) {
				double score = rateCard(validCards[i]);
				if( score > highestScore ) {
					highestScore = score;
					highest = i;
				}
			}
			return validCards[highest];
		}
       
        public int[] passThreeCards(int[] validCards) {
			double[] highestScore = new double[3];
			int[] highest = new int[3];
			for( int i = 0; i < 3; i++ ) {
				highestScore[i] = -Double.MAX_VALUE;
				highest[i] = i;
			}
			for( int i = 0; i < validCards.length; i++ ) {
				double score = rateCardPass(validCards[i]);
				int index = i;
				for( int j = 0; j < 3; j++ ){
					if( score > highestScore[j] ) {
						double temp = score;
						int tempIndex = index;
						score = highestScore[j];
						index = highest[j];						
						highestScore[j] = temp;
						highest[j] = tempIndex;
					}
				}
			}
			for(int i = 0; i < 3; i++){
				highest[i] = validCards[highest[i]];
			}
			return highest;
		}
       
        public void updatePlayedCards(int[][] played, int[] hand) {
                playedCards = played;
                userHand = hand;
        }
       
        public boolean hasCardBeenPlayed(int card) {
                for( int i = 0; i < 13; i++ ) {
                        for( int j = 0; j < 4; j++ ) {
                                if( card == playedCards[i][j] )
                                        return true;
                                else if( playedCards[i][j] == -1 )
                                        return false;
                        }
                }
                return false;
        }
       
        public boolean isValid(int card) {
                return true;
        }
		
		/* Abstract methods required to be implemented by user's bot code */
		
		public abstract double rateFirstCard(int card);
		
		public abstract double rateCard(int card);
		
		public abstract double rateCardPass(int card);
 
}
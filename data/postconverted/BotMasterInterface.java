package postconverted;

public interface BotMasterInterface {

	public 		int 		playFirstCard		(int[] validCards);
    
	public	 	int 		playCard			(int[] validCards);
   
    public 		int[] 		passThreeCards		(int[] validCards);
   
    public 		void 		updatePlayedCards	(int[][] played, int[] hand);
   
    public 		boolean 	hasCardBeenPlayed	(int card);
   
    public 		boolean 	isValid				(int card);
	
	public 		double 		rateFirstCard		(int card);
		
	public 		double 		rateCard			(int card);
		
	public 		double 		rateCardPass		(int card);
	
}
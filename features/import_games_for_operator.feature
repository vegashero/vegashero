Feature: Import games for operator

  Scenario: Game exists
    Given game exists
    And game has operator
    When I import games for a new operator
    Then no game is imported
    And the new operator is added to the list of operators for the game

  Scenario: Game does not exist

import unittest

from trump.deck import Deck
from trump.mark import Mark


class TestDeck(unittest.TestCase):
    def test_create(self):
        deck = Deck.create()

        markCards = {
            Mark.HEART: [],
            Mark.CLOVER: [],
            Mark.DIAMOND: [],
            Mark.SPADE: [],
        }
        for card in deck.cards:
            markCards[card.mark].append(card)

        for mark in Mark:
            self.assertEqual(13, len(markCards[mark]))

        for mark, cards in markCards.items():
            for card in cards:
                self.assertEqual(mark, card.mark)
                self.assertTrue(1 <= card.number.value <= 13)

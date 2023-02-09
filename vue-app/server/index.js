const express = require('express');
const cors = require('cors');
const app = express();

app.use(cors(
  {
    origin: 'http://localhost:3000',
    credentials: true
  }
));

app.post('/api/check-compagny', async (req, res) => {
  try {
    const { name, address, founder, siret, creationDate } = req.query;
    if (!name || !address || !founder || !siret || !creationDate) {
      return res.status(422).json({ message: 'missing data' });
    }

    if (
      typeof name !== 'string' ||
      typeof address !== 'string' ||
      typeof founder !== 'string' ||
      typeof siret !== 'number' ||
      typeof creationDate !== 'string'
    ) {
      return res.status(422).json({ message: 'invalid data' });
    }

    if (siret.length !== 14) {
      return res.status(422).json({ message: 'invalid siret' });
    }

    return res.status(200).json({ message: 'Entreprise valide' });

    //   if (compagny === 'test') {
    //   res.json({ compagny: 'test' });
    // } else {
    //   res.status(422).json({ compagny: 'not found' });
    // }
  } catch (error) {
    res.sendStatus(500);
    console.error(error);
  }
});

app.listen(5111, async () => {
  console.log('Server listening on port 5111');
});
